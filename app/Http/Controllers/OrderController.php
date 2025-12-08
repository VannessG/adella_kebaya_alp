<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Discount;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\RajaOngkirService;

class OrderController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function index(){
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        $statusOptions = Order::getStatusOptions();
        
        return view('order.index', [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function show($orderNumber){
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
            $productId = $request->product;
            $quantity = $request->quantity;
            
            $product = Product::find($productId);
            if ($product && $product->stock >= $quantity && $product->branch_id == $branch->id) {
                // Calculate discounted price
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
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->stock >= $item['quantity'] && $product->branch_id == $branch->id) {
                    // Calculate discounted price
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
            'totalPrice' => $discountedTotal, // Use discounted total
            'user' => Auth::user(),
            'paymentMethods' => $paymentMethods,
            'discount' => $activeDiscount,
            'isDirectCheckout' => $request->has('product'),
            'branch' => $branch
        ]);
    }

    // Helper method to calculate discounted price
    private function calculateDiscountedPrice($originalPrice, $discount)
    {
        if (!$discount) {
            return $originalPrice;
        }
        
        if ($discount->type === 'percentage') {
            return $originalPrice * (1 - ($discount->amount / 100));
        } else {
            // Fixed amount discount
            return max(0, $originalPrice - $discount->amount);
        }
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
        
        if ($request->has('direct_products')) {
            foreach ($request->direct_products as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product && $product->stock >= $quantity && $product->branch_id == $branch->id) {
                    // Calculate discounted price
                    $discountedPrice = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                    $subtotal = $discountedPrice * $quantity;
                    $totalAmount += $subtotal;
                    
                    $orderProducts[$productId] = [
                        'quantity' => $quantity,
                        'price' => $discountedPrice, // Save discounted price
                        'original_price' => $product->price // Save original price for reference
                    ];
                    $product->decrement('stock', $quantity);
                }
            }
        } else {
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->stock >= $item['quantity'] && $product->branch_id == $branch->id) {
                    // Calculate discounted price
                    $discountedPrice = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                    $subtotal = $discountedPrice * $item['quantity'];
                    $totalAmount += $subtotal;
                    
                    $orderProducts[$productId] = [
                        'quantity' => $item['quantity'],
                        'price' => $discountedPrice, // Save discounted price
                        'original_price' => $product->price // Save original price for reference
                    ];
                    $product->decrement('stock', $item['quantity']);
                }
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
            // Ambil district tujuan dari request
            $destinationDistrictId = $request->input('district_id');
            // Hitung total berat produk
            $totalWeight = 0;
            foreach ($orderProducts as $productId => $item) {
                $product = Product::find($productId);
                $totalWeight += ($product->weight ?? 0) * $item['quantity'];
            }
            // Ambil origin dari config
            $originDistrictId = config('services.rajaongkir.origin_district_id', 3732);
            // Ambil kode kurir dari request (misal: 'jne', 'pos', 'tiki')
            $courierCode = $request->input('courier_code', 'jne');
            // Hitung biaya ongkir via RajaOngkirService
            $shippingOptions = $this->rajaOngkirService->calculateShippingCost($originDistrictId, $destinationDistrictId, $totalWeight, $courierCode);
            // Pilih service yang dipilih user (misal: 'REG')
            $selectedService = $request->input('courier_service', 'REG');
            $shippingCost = 0;
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
            'discount_id' => $activeDiscount ? $activeDiscount->id : null, // Save discount reference
        ]);
        
        $order->products()->sync($orderProducts);
        
        // Increment discount usage if used
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
        $this->sendWhatsappNotification($order, $payment);
        
        if (!$request->has('direct_products')) {
            session()->forget('cart');
        }
        
        return redirect()->route('pesanan.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function cancel(Order $order){
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
        
        // Create review form data
        session(['review_order_id' => $order->id]);
        
        return redirect()->route('reviews.create')
            ->with('success', 'Pesanan berhasil diselesaikan. Silakan berikan review.');
    }
    
    private function sendWhatsappNotification($order, $payment)
    {
        $message = "📦 *PESANAN BARU* 📦\n";
        $message .= "No. Pesanan: {$order->order_number}\n";
        $message .= "Pelanggan: {$order->customer_name}\n";
        $message .= "Telepon: {$order->customer_phone}\n";
        $message .= "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";
        $message .= "Metode Pembayaran: {$payment->paymentMethod->name}\n";
        $message .= "Status: Menunggu Pembayaran";
        
        // Simpan ke database
        \App\Models\WhatsappNotification::create([
            'phone_number' => config('app.admin_whatsapp', '6281234567890'),
            'message' => $message,
            'type' => 'order',
            'reference_id' => $order->id,
            'status' => 'pending'
        ]);
    }
}