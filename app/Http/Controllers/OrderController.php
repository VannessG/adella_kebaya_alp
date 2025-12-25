<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Discount;
use App\Models\Shipment;
use App\Models\Rent;
use App\Models\WhatsappNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\RajaOngkirService;
use App\Services\MidtransService;

class OrderController extends Controller
{
    protected $rajaOngkirService;

    // --- ID KECAMATAN ASAL (Hardcode sesuai Cabang) ---
    const ORIGIN_WARU = 6626;       // Cabang Surabaya
    const ORIGIN_BOJONEGORO = 953;  // Cabang Bojonegoro

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    // --- AJAX HELPER UNTUK DROPDOWN ---
    public function getProvinces() {
        return response()->json($this->rajaOngkirService->getProvinces());
    }
    public function getCities($provinceId) {
        return response()->json($this->rajaOngkirService->getCities($provinceId));
    }
    public function getDistricts($cityId) {
        return response()->json($this->rajaOngkirService->getDistricts($cityId));
    }

    // ==========================================
    // 1. HALAMAN FORM CHECKOUT
    // ==========================================
    public function checkoutForm(Request $request)
{
    $branch = session('selected_branch');
    if (!$branch) {
        return redirect()->route('select.branch')->with('error', 'Silakan pilih cabang terlebih dahulu.');
    }

    session()->forget(['is_direct_checkout', 'direct_checkout_item']);

    $cartItems = [];
    $totalPrice = 0;

    // AMBIL SEMUA DISKON AKTIF (Untuk Dropdown)
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
        'user' => Auth::user(), // Menggunakan Facade Auth lebih stabil
        'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        'discounts' => $discounts, 
        'branch' => $branch,
        'provinces' => $this->rajaOngkirService->getProvinces()
    ]);
}

    // ==========================================
    // 2. PROSES CHECKOUT (SIMPAN ORDER) --> INI YANG HILANG TADI
    // ==========================================
    public function checkout(Request $request)
{
    $branch = session('selected_branch');
    
    // Log diagnostik untuk memastikan data masuk
    Log::info("=== DIAGNOSA DATA MASUK ===");
    Log::info("Input direct_products: " . json_encode($request->direct_products));
    Log::info("Session cart: " . json_encode(session()->get('cart')));

    if (!$branch) {
        return redirect()->route('select.branch')->with('error', 'Silakan pilih cabang terlebih dahulu.');
    }

    $request->validate([
        'customer_name' => 'required|string',
        'customer_phone' => 'required|string',
        'customer_address' => 'required|string',
        'delivery_type' => 'required|in:pickup,delivery',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'district_id' => 'required_if:delivery_type,delivery',
    ]);

    $orderProducts = [];
    $totalAmount = 0;
    $totalWeight = 0;

    // AMBIL DATA DENGAN PRIORITAS: 
    // 1. Cek Request Direct Products (Beli Sekarang)
    // 2. Cek Session Cart (Keranjang)
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
            // Gunakan tipe data yang sama untuk perbandingan (int)
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

    // Hitung Ongkir
    $shippingCost = 0;
    if ($request->delivery_type === 'delivery') {
        $originId = str_contains(strtolower($branch->name), 'bojonegoro') ? self::ORIGIN_BOJONEGORO : self::ORIGIN_WARU;
        $costs = $this->rajaOngkirService->calculateShippingCost($originId, $request->district_id, max($totalWeight, 1), $request->courier_code);
        foreach($costs as $c) {
            if($c['service'] == $request->courier_service) {
                $shippingCost = $c['cost'];
                break;
            }
        }
    }

    // LOGIKA DISKON PILIHAN USER (SINKRON DENGAN DROPDOWN)
    $discountAmount = 0;
    $appliedDiscount = null;
    if ($request->filled('discount_id')) {
        $appliedDiscount = Discount::find($request->discount_id);
        if ($appliedDiscount && $appliedDiscount->isActive()) {
            $discountAmount = $appliedDiscount->applyTo($totalAmount);
        }
    }

    $finalTotal = ($totalAmount + $shippingCost) - $discountAmount;

    return DB::transaction(function () use ($request, $branch, $finalTotal, $shippingCost, $orderProducts, $appliedDiscount) {
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(5)) . '-' . time(),
            'user_id' => Auth::id(),
            'branch_id' => $branch->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'status' => 'pending',
            'order_date' => now(),
            'total_amount' => $finalTotal,
            'shipping_cost' => $shippingCost,
            'delivery_type' => $request->delivery_type,
            'discount_id' => $appliedDiscount ? $appliedDiscount->id : null,
        ]);

        foreach ($orderProducts as $id => $details) {
            $order->products()->attach($id, $details);
            Product::find($id)->decrement('stock', $details['quantity']);
        }

        if ($appliedDiscount) { $appliedDiscount->increment('used_count'); }

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
                return redirect()->route('payment.pay', $payment->payment_number);
            } catch (\Exception $e) { Log::error($e->getMessage()); }
        }

        return redirect()->route('pesanan.show', $order->order_number)->with('success', 'Berhasil!');
    });
}

    // ==========================================
    // 3. AJAX API HITUNG ONGKIR
    // ==========================================
    public function getShippingCost(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer',
            'courier' => 'required|string',
        ]);

        $branch = session('selected_branch');
        $originId = str_contains(strtolower($branch->name ?? ''), 'bojonegoro') ? self::ORIGIN_BOJONEGORO : self::ORIGIN_WARU;

        // Hitung total berat dari session (untuk akurasi ongkir)
        $totalWeight = 0;
        $cart = session('is_direct_checkout') ? [session('direct_checkout_item')] : session('cart', []);
        
        foreach ($cart as $id => $details) {
            $pid = is_array($details) ? ($details['id'] ?? $id) : $id;
            $qty = is_array($details) ? ($details['quantity'] ?? 1) : $details;
            $product = Product::find($pid);
            if ($product) $totalWeight += ($product->weight ?? 1000) * $qty;
        }

        $costs = $this->rajaOngkirService->calculateShippingCost($originId, $request->district_id, max($totalWeight, 1), $request->courier);

        return response()->json(['costs' => $costs]);
    }

    // --- HELPER PRICE ---
    private function calculateDiscountedPrice($originalPrice, $discount)
    {
        if (!$discount) return $originalPrice;
        if ($discount->type === 'percentage') {
            return $originalPrice * (1 - ($discount->amount / 100));
        }
        return max(0, $originalPrice - $discount->amount);
    }
    
    // --- METHOD BAWAAN LAIN (INDEX, SHOW, CANCEL) TETAP PERLU ADA ---
    // Pastikan Anda menyalin method index, show, cancel, complete dari file lama Anda 
    // agar fitur riwayat pesanan tetap jalan.
    
    // ==========================================
    // 1. RIWAYAT PESANAN (FIX ERROR NAV BAR)
    // ==========================================

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) return back()->with('error', 'Akses ditolak.');
        if ($order->status !== 'pending') return back()->with('error', 'Pesanan tidak bisa dibatalkan.');

        DB::transaction(function () use ($order) {
            foreach ($order->products as $product) { $product->increment('stock', $product->pivot->quantity); }
            $order->update(['status' => 'cancelled']);
            $order->payments()->update(['status' => 'failed']);
        });
        return back()->with('success', 'Pesanan dibatalkan.');
    }

    public function complete(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'shipping') return back()->with('error', 'Gagal.');
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan selesai.');
    }
    public function index()
    {
        // Mengambil order milik user login beserta produk dan review terkaitnya
        $orders = Order::with(['products', 'reviews']) 
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Ambil status options langsung dari Model
        $statusOptions = Order::getStatusOptions();

        return view('order.index', compact('orders', 'statusOptions'));
    }

    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'payment_check' => 'Cek Pembayaran',
            'processing' => 'Diproses',
            'shipping' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return view('order.show', [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'statusOptions' => $statusOptions
        ]);
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    /**
     * Menampilkan daftar semua pesanan untuk Admin
     */
    public function adminIndex()
    {
        // Menggunakan paginate(10) karena di view Anda ada pengecekan $orders->hasPages()
        $orders = Order::with('user')->latest()->paginate(10);
        
        $statusOptions = Order::getStatusOptions();

        return view('admin.orders.index', compact('orders', 'statusOptions'));
    }

    /**
     * Menampilkan detail pesanan untuk Admin
     */
    public function showAdmin(Order $order)
    {
        $statusOptions = Order::getStatusOptions();
        
        return view('admin.orders.show', [
            'order' => $order->load(['products', 'user', 'payments.paymentMethod', 'shipment']),
            'statusOptions' => $statusOptions
        ]);
    }

    /**
     * Update status pesanan dari dropdown di tabel admin
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatusOptions())),
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . substr($order->order_number, -6) . ' berhasil diperbarui.');
    }

    /**
     * Halaman form buat pesanan manual oleh admin
     */
    public function createAdmin()
    {
        $products = Product::where('stock', '>', 0)->get();
        $branches = \App\Models\Branch::all();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('admin.orders.create', compact('products', 'branches', 'paymentMethods'));
    }

    /**
     * Simpan pesanan yang dibuat oleh admin
     */
    public function storeAdmin(Request $request)
    {
        // Logika simpan pesanan admin biasanya mirip dengan checkout
        // Namun disesuaikan jika admin bisa memilih user atau input manual
        // Untuk sementara, Anda bisa mengarahkan ke logika checkout atau membuat logic khusus admin di sini.
        return back()->with('error', 'Fitur simpan pesanan admin sedang dalam pengembangan.');
    }
}