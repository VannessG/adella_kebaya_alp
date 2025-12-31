<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;

class OrderController extends Controller{
    protected $rajaOngkirService;

    const ORIGIN_WARU = 6626;       // Cabang Surabaya
    const ORIGIN_BOJONEGORO = 953;  // Cabang Bojonegoro

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces() {
        return response()->json($this->rajaOngkirService->getProvinces());
    }

    public function getCities($provinceId) {
        return response()->json($this->rajaOngkirService->getCities($provinceId));
    }

    public function getDistricts($cityId) {
        return response()->json($this->rajaOngkirService->getDistricts($cityId));
    }

    public function checkoutForm(Request $request){
        $branch = session('selected_branch');
        if (!$branch) {
            return redirect()->route('select.branch')->with('error', 'Silakan pilih cabang terlebih dahulu.');
        }

        session()->forget(['is_direct_checkout', 'direct_checkout_item']);
        $cartItems = [];
        $totalPrice = 0;
        $discounts = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get()
            ->filter(function ($discount) {
                return is_null($discount->max_usage) || $discount->used_count < $discount->max_usage;
            });

        if ($request->has('product') && $request->has('quantity')) {
            $product = Product::find($request->product);
            if ($product && $product->stock >= $request->quantity && $product->branch_id == $branch->id) {
                session([
                    'is_direct_checkout' => true,
                    'direct_checkout_item' => ['id' => $product->id, 'quantity' => $request->quantity]
                ]);

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => $product->image_url,
                    'quantity' => $request->quantity,
                    'subtotal' => $product->price * $request->quantity,
                    'max_stock' => $product->stock,
                    'weight' => $product->weight,
                    'is_direct_checkout' => true
                ];
                $totalPrice = $product->price * $request->quantity;
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->stock >= $item['quantity'] && $product->branch_id == $branch->id) {
                    $subtotal = $product->price * $item['quantity'];
                    $cartItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image_url' => $product->image_url,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal,
                        'max_stock' => $product->stock,
                        'weight' => $product->weight,
                        'is_direct_checkout' => false
                    ];
                    $totalPrice += $subtotal;
                }
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        return view('order.checkout', [
            'title' => 'Checkout Pesanan',
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'user' => Auth::user(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            'discounts' => $discounts, 
            'branch' => $branch,
            'provinces' => $this->rajaOngkirService->getProvinces()
        ]);
    }

    public function checkout(Request $request){
        $branch = session('selected_branch');

        if (!$branch) {
            return redirect()->route('select.branch')->with('error', 'Silakan pilih cabang terlebih dahulu.');
        }

        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'city_id' => 'required_if:delivery_type,delivery',
            'district_id' => 'required_if:delivery_type,delivery',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $orderProducts = [];
        $totalAmount = 0;
        $totalWeight = 0;
        $itemsRaw = [];
        if ($request->filled('direct_products')) {
            $itemsRaw = $request->direct_products; 
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $id => $details) {
                $itemsRaw[$id] = $details['quantity'];
            }
        }

        foreach ($itemsRaw as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product && $product->stock >= $quantity) {
                if ((int)$product->branch_id === (int)$branch->id) {
                    $orderProducts[$productId] = [
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'original_price' => $product->price
                    ];
                    $totalAmount += ($product->price * $quantity);
                    $totalWeight += ($product->weight ?? 1000) * $quantity;
                }
            }
        }

        if (empty($orderProducts)) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan atau tidak tersedia di cabang ini.');
        }

        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $originId = str_contains(strtolower($branch->name), 'bojonegoro') ? self::ORIGIN_BOJONEGORO : self::ORIGIN_WARU;

            $costs = $this->rajaOngkirService->calculateShippingCost(
                $originId, 
                $request->district_id, 
                max($totalWeight, 1), 
                $request->courier_code,
                $request->city_id
            );

            foreach($costs as $c) {
                if($c['service'] == $request->courier_service) {
                    $shippingCost = $c['cost'];
                    break;
                }
            }
        }

        $discountAmount = 0;
        $appliedDiscount = null;
        if ($request->filled('discount_id')) {
            $appliedDiscount = Discount::find($request->discount_id);
            if ($appliedDiscount && $appliedDiscount->isActive()) {
                $discountAmount = $appliedDiscount->applyTo($totalAmount);
            }
        }

        return DB::transaction(function () use ($request, $branch, $orderProducts, $appliedDiscount, $totalAmount, $shippingCost, $discountAmount) {
            $finalTotal = ($totalAmount + $shippingCost) - $discountAmount;

            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Jika ada bukti, status 'payment_check', jika tidak 'pending'
            $statusOrder = $paymentProofPath ? 'payment_check' : 'pending';

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(5)) . '-' . time(),
                'user_id' => Auth::id(),
                'branch_id' => $branch->id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'status' => $statusOrder,
                'order_date' => now(),
                'subtotal' => $totalAmount, // SIMPAN HARGA ASLI
                'discount_amount' => $discountAmount, // SIMPAN POTONGAN
                'total_amount' => $finalTotal,
                'shipping_cost' => $shippingCost,
                'delivery_type' => $request->delivery_type,
                'discount_id' => $appliedDiscount ? $appliedDiscount->id : null,
                'payment_proof' => $paymentProofPath,
            ]);

            foreach ($orderProducts as $id => $details) {
                $order->products()->attach($id, $details);
                Product::find($id)->decrement('stock', $details['quantity']);
            }

            if ($appliedDiscount) { 
                $appliedDiscount->increment('used_count'); 
            }  

            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $payment = $order->payments()->create([
                'payment_number' => 'PAY-' . strtoupper(Str::random(5)),
                'payment_method_id' => $paymentMethod->id,
                'amount' => $finalTotal,
                'payer_name' => $request->customer_name,
                'payer_phone' => $request->customer_phone,
                'status' => 'pending'
            ]);

            session()->forget(['cart', 'is_direct_checkout', 'direct_checkout_item']);
            if (in_array($paymentMethod->type, ['qris', 'va'])) {
                try {
                    $midtrans = new MidtransService();
                    $snapToken = $midtrans->createTransaction($payment); 
                    $payment->update(['snap_token' => $snapToken]);
                    // Redirect ke halaman bayar jika QRIS
                    return redirect()->route('payment.pay', $payment->payment_number);
                } catch (\Exception $e) { Log::error($e->getMessage()); }
            }
            return redirect()->route('pesanan.show', $order->order_number)->with('success', 'Berhasil!');
        });
    }

    public function getShippingCost(Request $request){
        $request->validate([
            'district_id' => 'required',
            'city_id'     => 'required',
            'courier'     => 'required',
        ]);

        $branch = session('selected_branch');
        $isBojonegoro = str_contains(strtolower($branch->name ?? ''), 'bojonegoro');
        $originDistrictId = $isBojonegoro ? 953 : 6626;

        $costs = $this->rajaOngkirService->calculateShippingCost(
            $originDistrictId, 
            $request->input('district_id'), 
            1000, 
            $request->input('courier'),
            $request->input('city_id') 
        );
        return response()->json(['costs' => $costs]);
    }

    private function calculateDiscountedPrice($originalPrice, $discount)
    {
        if (!$discount) return $originalPrice;
        if ($discount->type === 'percentage') {
            return $originalPrice * (1 - ($discount->amount / 100));
        }
        return max(0, $originalPrice - $discount->amount);
    }

    public function cancel(Order $order){
        if ($order->user_id !== Auth::id()) return back()->with('error', 'Akses ditolak.');
        if ($order->status !== 'pending') return back()->with('error', 'Pesanan tidak bisa dibatalkan.');

        DB::transaction(function () use ($order) {
            foreach ($order->products as $product) { $product->increment('stock', $product->pivot->quantity); }
            $order->update(['status' => 'cancelled']);
            $order->payments()->update(['status' => 'failed']);
        });
        return back()->with('success', 'Pesanan dibatalkan.');
    }

    public function complete(Order $order){
        if ($order->user_id !== Auth::id() || $order->status !== 'shipping') return back()->with('error', 'Gagal.');
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan selesai.');
    }

    public function index(){
        // 1. Ambil data order milik user (sesuaikan dengan query Anda sebelumnya)
        // Pastikan meload relasi 'products' agar tidak N+1 Query
        $orders = Order::where('user_id', Auth::id())
            ->with('products') 
            ->latest()
            ->get(); // Atau ->paginate(10) jika pakai pagination

        // 2. STATUS OPTIONS (Definisikan di sini agar view tidak error)
        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'awaiting_payment' => 'Menunggu Pembayaran'
        ];

        // 3. TRANSFORMASI DATA (Pindahkan logika PHP View ke sini)
        // Jika menggunakan paginate(), ganti $orders->transform(...) menjadi $orders->getCollection()->transform(...)
        $orders->transform(function ($order) {
            
            // A. Format Tanggal (Ganti Carbon di View)
            $order->formatted_date = date('d M Y', strtotime($order->order_date));

            // B. Format Nama Produk Pendek (Ganti Str::limit di View)
            // Kita loop produk di dalam order ini untuk menambahkan property short_name
            $order->products->transform(function ($product) {
                $product->short_name = \Illuminate\Support\Str::limit($product->name, 10);
                return $product;
            });

            return $order;
        });

        return view('order.index', [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
            'statusOptions' => $statusOptions
        ]);
    }

    public function show($orderNumber){
        $order = Order::with(['products', 'payment.paymentMethod'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('order.show', [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'statusOptions' => Order::getStatusOptions(),
            'paymentMethods' => $paymentMethods 
        ]);
    }

    public function adminIndex(){
        $orders = Order::with('user')->latest()->paginate(10);
        $statusOptions = Order::getStatusOptions();
        return view('admin.orders.index', compact('orders', 'statusOptions'));
    }

    public function showAdmin(Order $order){
        $statusOptions = Order::getStatusOptions();
        return view('admin.orders.show', [
            'order' => $order->load(['products', 'user', 'payments.paymentMethod']),
            'statusOptions' => $statusOptions
        ]);
    }

    public function updateStatus(Request $request, Order $order){
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatusOptions())),
        ]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan #' . substr($order->order_number, -6) . ' berhasil diperbarui.');
    }

    public function storeAdmin(Request $request){
        $request->validate([
            'customer_name'    => 'required|string',
            'customer_phone'   => 'required|string',
            'customer_address' => 'required|string',
            'delivery_type'    => 'required|in:pickup,delivery',
            'products'         => 'required|array',
            'products.*.id'    => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $branch = session('selected_branch');
            $totalAmount = 0;
            $orderProductsData = [];

            // 2. Hitung Total dan Siapkan Data Produk
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;
                $orderProductsData[$product->id] = [
                    'quantity'       => $item['quantity'],
                    'price'          => $product->price,
                    'original_price' => $product->price,
                ];
            }

            // 3. Logika Ongkir
            $shippingCost = 0;
            if ($request->delivery_type === 'delivery') {
                // Menghapus format Rupiah jika ada
                $shippingCost = (int) str_replace(['Rp', '.', ' '], '', $request->shipping_cost);
            }

            // 4. Buat Order Utama
            $order = Order::create([
                'order_number'     => 'ADM-' . strtoupper(Str::random(5)) . '-' . time(),
                'user_id'          => Auth::id(),
                'branch_id'        => $branch->id,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'status'           => 'processing',
                'order_date'       => now(),
                'total_amount'     => $totalAmount + $shippingCost,
                'shipping_cost'    => $shippingCost,
                'delivery_type'    => $request->delivery_type,
            ]);

            // 5. Simpan ke Tabel Pivot & Potong Stok
            foreach ($orderProductsData as $productId => $details) {
                // Attach sekarang menyertakan original_price
                $order->products()->attach($productId, $details);
                Product::find($productId)->decrement('stock', $details['quantity']);
            }
            return redirect()->route('admin.orders.index')->with('success', 'Pesanan admin berhasil dibuat.');
        });
    }
}