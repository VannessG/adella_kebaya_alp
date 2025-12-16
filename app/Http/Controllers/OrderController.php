<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Discount;
use App\Models\Branch;
use App\Models\Shipment;
use App\Models\Rent;
use App\Models\WhatsappNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;

class OrderController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    // ==========================================
    // USER METHODS (Front End)
    // ==========================================

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $statusOptions = Order::getStatusOptions();
        
        return view('order.index', [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $statusOptions = Order::getStatusOptions();
        
        return view('order.show', [
            'title' => 'Detail Pesanan ' . $orderNumber,
            'order' => $order,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function checkoutForm(Request $request)
    {
        $branch = session('selected_branch');
        $cart = session()->get('cart', []);
        $cartItems = [];
        $totalPrice = 0;
        $discountedTotal = 0;
        
        // Get active discount
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        
        if ($request->has('product') && $request->has('quantity')) {
            // Direct Checkout (Beli Sekarang)
            $productId = $request->product;
            $quantity = $request->quantity;
            
            $product = Product::find($productId);
            if ($product && $product->stock >= $quantity && $product->branch_id == $branch->id) {
                $discountedPrice = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'discounted_price' => $discountedPrice,
                    'image' => $product->image,
                    'image_url' => $product->image_url,
                    'quantity' => $quantity,
                    'subtotal' => $discountedPrice * $quantity,
                    'max_stock' => $product->stock,
                    'is_direct_checkout' => true
                ];
                $totalPrice = $product->price * $quantity;
                $discountedTotal = $discountedPrice * $quantity;
            }
        } else {
            // Checkout dari Keranjang
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->stock >= $item['quantity'] && $product->branch_id == $branch->id) {
                    $discountedPrice = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                    
                    $cartItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'discounted_price' => $discountedPrice,
                        'image' => $product->image,
                        'image_url' => $product->image_url,
                        'quantity' => $item['quantity'],
                        'subtotal' => $discountedPrice * $item['quantity'],
                        'max_stock' => $product->stock,
                        'is_direct_checkout' => false
                    ];
                    $totalPrice += $product->price * $item['quantity'];
                    $discountedTotal += $discountedPrice * $item['quantity'];
                }
            }
        }
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang valid untuk checkout.');
        }
        
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return view('order.checkout', [
            'title' => 'Checkout Pesanan',
            'cartItems' => $cartItems,
            'totalPrice' => $discountedTotal,
            'user' => Auth::user(),
            'paymentMethods' => $paymentMethods,
            'discount' => $activeDiscount,
            'isDirectCheckout' => $request->has('product'),
            'branch' => $branch
        ]);
    }

    public function checkout(Request $request)
    {
        $branch = session('selected_branch');
        $cart = session()->get('cart', []);
        $totalAmount = 0;
        $orderProducts = [];
        
        // Get active discount
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        
        // Logic untuk Direct Buy atau Cart Buy
        $itemsToProcess = $request->has('direct_products') ? $request->direct_products : [];
        
        if ($request->has('direct_products')) {
            foreach ($request->direct_products as $productId => $quantity) {
                $this->processCheckoutItem($productId, $quantity, $branch, $activeDiscount, $totalAmount, $orderProducts);
            }
        } else {
            foreach ($cart as $productId => $item) {
                $this->processCheckoutItem($productId, $item['quantity'], $branch, $activeDiscount, $totalAmount, $orderProducts);
            }
        }

        if (empty($orderProducts)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang valid untuk checkout.');
        }
        
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Hitung shipping cost dinamis
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $destinationDistrictId = $request->input('district_id');
            $totalWeight = 0;
            foreach ($orderProducts as $productId => $item) {
                $product = Product::find($productId);
                $totalWeight += ($product->weight ?? 0) * $item['quantity'];
            }
            // Default ke 3732 (Magelang) jika tidak ada config
            $originDistrictId = config('services.rajaongkir.origin_district_id', 3732);
            $courierCode = $request->input('courier_code', 'jne');
            
            $shippingOptions = $this->rajaOngkirService->calculateShippingCost($originDistrictId, $destinationDistrictId, $totalWeight, $courierCode);
            
            $selectedService = $request->input('courier_service', 'REG');
            foreach ($shippingOptions as $option) {
                if ($option['service'] === $selectedService) {
                    $shippingCost = $option['cost'];
                    break;
                }
            }
        }
        
        $totalAmount += $shippingCost;
        
        // Create order
        $order = Order::create([
            'order_number' => 'KBY-' . strtoupper(Str::random(3)) . '-' . time(),
            'user_id' => Auth::id(),
            'branch_id' => $branch->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'status' => 'pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'shipping_cost' => $shippingCost,
            'delivery_type' => $request->delivery_type,
            'discount_id' => $activeDiscount ? $activeDiscount->id : null,
        ]);
        
        $order->products()->sync($orderProducts);
        
        if ($activeDiscount) {
            $activeDiscount->increment('used_count');
        }
        
        // Create payment
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        $proofImage = null;
        
        if ($request->hasFile('proof_image')) {
            $proofImage = $request->file('proof_image')->store('payments', 'public');
        }
        
        $payment = $order->payments()->create([
            'payment_number' => 'PAY-' . strtoupper(Str::random(3)) . '-' . time(),
            'payment_method_id' => $paymentMethod->id,
            'amount' => $totalAmount,
            'payer_name' => $request->customer_name,
            'payer_phone' => $request->customer_phone,
            'proof_image' => $proofImage,
            'status' => $paymentMethod->type === 'qris' ? 'pending' : 'processing',
        ]);
        
        // Kirim notifikasi WhatsApp
        $this->sendOrderNotification($order, $payment);
        
        // INTERCEPT MIDTRANS
        if ($paymentMethod->type === 'qris') {
            try {
                $midtransService = new MidtransService();
                $snapToken = $midtransService->createTransaction($payment);
                $payment->update(['snap_token' => $snapToken]);

                // Clear Cart
                if (!$request->has('direct_products')) {
                    session()->forget('cart');
                }

                // Redirect ke halaman bayar
                return redirect()->route('payment.pay', $payment->payment_number);

            } catch (\Exception $e) {
                return redirect()->route('pesanan.show', $order->order_number)
                    ->with('error', 'Pesanan dibuat, tapi Gagal koneksi ke Midtrans: ' . $e->getMessage());
            }
        }

        if (!$request->has('direct_products')) {
            session()->forget('cart');
        }
        
        return redirect()->route('pesanan.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function cancel(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }
        
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);
        }
        
        $order->update(['status' => 'cancelled']);
        
        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
    
    public function complete(Order $order, Request $request)
    {
        if (!$order->canBeCompleted()) {
            return redirect()->back()->with('error', 'Pesanan belum dapat diselesaikan.');
        }
        
        $order->update(['status' => 'completed']);
        session(['review_order_id' => $order->id]);
        
        return redirect()->route('reviews.create')
            ->with('success', 'Pesanan berhasil diselesaikan. Silakan berikan review.');
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    public function adminIndex()
    {
        $branch = session('selected_branch');
        $orders = Order::with(['user', 'products', 'branch'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->paginate(10);
            
        $statusOptions = Order::getStatusOptions();
        
        return view('admin.orders.index', [
            'title' => 'Manajemen Pesanan',
            'orders' => $orders,
            'statusOptions' => $statusOptions,
        ]);
    }
    
    public function createAdmin()
    {
        $products = Product::where('is_available', true)->where('stock', '>', 0)->get();
        $provinces = $this->rajaOngkirService->getProvinces();
        return view('admin.orders.create', [
            'products' => $products,
            'provinces' => $provinces
        ]);
    }
    
    public function storeAdmin(Request $request)
    {
        $branch = session('selected_branch');
        
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1'
        ]);
        
        $totalAmount = 0;
        $orderProducts = [];
        
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            if ($product && $product->stock >= $item['quantity']) {
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;
                
                $orderProducts[$product->id] = [
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ];
                $product->decrement('stock', $item['quantity']);
            }
        }
        
        if (empty($orderProducts)) {
            return redirect()->back()->with('error', 'Tidak ada produk yang valid.');
        }
        
        // Hitung shipping cost (manual flat rate untuk admin create saat ini)
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $shippingCost = 20000; // Default flat rate untuk admin manual input
        }
        
        $totalAmount += $shippingCost;
        
        $order = Order::create([
            'order_number' => 'KBY-' . strtoupper(Str::random(3)) . '-' . time(),
            'user_id' => Auth::id(),
            'branch_id' => $branch->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'status' => 'pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'shipping_cost' => $shippingCost,
            'delivery_type' => $request->delivery_type,
        ]);
        
        $order->products()->sync($orderProducts);
        
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }
    
    public function showAdmin(Order $order)
    {
        $statusOptions = Order::getStatusOptions();
        
        return view('admin.orders.show', [
            'title' => 'Detail Pesanan ' . $order->order_number,
            'order' => $order,
            'statusOptions' => $statusOptions,
        ]);
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,payment_check,processing,shipping,completed,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->update($validated);
        
        // Jika status berubah ke completed, kirim notifikasi WhatsApp
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $this->sendGeneralNotification($order->user->phone, 
                "Pesanan {$order->order_number} telah selesai. Silakan berikan review produk.");
        }
        
        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // ==========================================
    // SHIPMENT METHODS (Admin)
    // ==========================================
    
    public function shipmentsIndex()
    {
        $shipments = Shipment::with(['branch', 'transaction'])
            ->latest()
            ->paginate(10);
            
        $statusOptions = Shipment::getStatusOptions();
        
        return view('admin.shipments.index', [
            'title' => 'Manajemen Pengiriman',
            'shipments' => $shipments,
            'statusOptions' => $statusOptions
        ]);
    }
    
    public function updateShipmentStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,driver_assigned,picked_up,on_delivery,delivered,cancelled'
        ]);
        
        $shipment->update($validated);
        
        // Jika status delivered, update timestamp
        if ($validated['status'] === 'delivered') {
            $shipment->update(['delivered_time' => now()]);
            
            // Update status order/rent
            $transaction = $shipment->transaction;
            if ($transaction instanceof Order) {
                $transaction->update(['status' => 'shipping']);
                $this->sendGeneralNotification($transaction->user->phone, 
                    "Pesanan {$transaction->order_number} sudah dalam pengiriman. Silakan konfirmasi saat barang diterima.");
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'active']);
                $this->sendGeneralNotification($transaction->user->phone, 
                    "Sewa {$transaction->rent_number} sudah aktif. Barang sedang dalam perjalanan.");
            }
        }
        
        return redirect()->route('admin.shipments.index')->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    // ==========================================
    // API METHODS (RajaOngkir & Helpers)
    // ==========================================

    public function getCities($provinceId)
    {
        $cities = $this->rajaOngkirService->getCitiesByProvince($provinceId);
        return response()->json(['data' => $cities]);
    }

    public function getDistricts($cityId)
    {
        $districts = $this->rajaOngkirService->getDistrictsByCity($cityId);
        return response()->json(['data' => $districts]);
    }

    public function getShippingCost(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer',
            'courier' => 'required|string',
            'weight' => 'required|integer|min:1'
        ]);
        $originDistrictId = config('services.rajaongkir.origin_district_id', 3732);
        $costs = $this->rajaOngkirService->calculateShippingCost(
            $originDistrictId,
            $request->district_id,
            $request->weight,
            $request->courier
        );
        return response()->json(['costs' => $costs]);
    }

    // ==========================================
    // PRIVATE HELPERS
    // ==========================================

    private function calculateDiscountedPrice($originalPrice, $discount)
    {
        if (!$discount) {
            return $originalPrice;
        }
        
        if ($discount->type === 'percentage') {
            return $originalPrice * (1 - ($discount->amount / 100));
        } else {
            return max(0, $originalPrice - $discount->amount);
        }
    }

    private function processCheckoutItem($productId, $quantity, $branch, $activeDiscount, &$totalAmount, &$orderProducts) 
    {
        $product = Product::find($productId);
        if ($product && $product->stock >= $quantity && $product->branch_id == $branch->id) {
            $discountedPrice = $this->calculateDiscountedPrice($product->price, $activeDiscount);
            $subtotal = $discountedPrice * $quantity;
            $totalAmount += $subtotal;
            
            $orderProducts[$productId] = [
                'quantity' => $quantity,
                'price' => $discountedPrice,
                'original_price' => $product->price
            ];
            $product->decrement('stock', $quantity);
        }
    }

    // Notifikasi khusus saat Order dibuat (User)
    private function sendOrderNotification($order, $payment)
    {
        $message = "📦 *PESANAN BARU* 📦\n";
        $message .= "No. Pesanan: {$order->order_number}\n";
        $message .= "Pelanggan: {$order->customer_name}\n";
        $message .= "Telepon: {$order->customer_phone}\n";
        $message .= "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";
        $message .= "Metode Pembayaran: {$payment->paymentMethod->name}\n";
        $message .= "Status: Menunggu Pembayaran";
        
        WhatsappNotification::create([
            'phone_number' => config('app.admin_whatsapp', '6281234567890'),
            'message' => $message,
            'type' => 'order',
            'reference_id' => $order->id,
            'status' => 'pending'
        ]);
    }

    // Notifikasi umum (bisa untuk admin update status atau sistem)
    private function sendGeneralNotification($phoneNumber, $message)
    {
        try {
            WhatsappNotification::create([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'type' => 'notification',
                'reference_id' => 0,
                'status' => 'pending'
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            return false;
        }
    }
}