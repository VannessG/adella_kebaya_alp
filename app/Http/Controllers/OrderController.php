<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\PaymentMethod;
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
        
        // Reset Session Direct Buy Lama
        session()->forget(['is_direct_checkout', 'direct_checkout_item']);

        $cartItems = [];
        $totalPrice = 0;
        
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        
        // --- LOGIKA: BELI SEKARANG (DIRECT) ---
        if ($request->has('product') && $request->has('quantity')) {
            $productId = $request->product;
            $quantity = $request->quantity;
            
            session([
                'is_direct_checkout' => true,
                'direct_checkout_item' => ['id' => $productId, 'quantity' => $quantity]
            ]);

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
                $totalPrice = $discountedPrice * $quantity;
            }
        } 
        // --- LOGIKA: DARI KERANJANG ---
        else {
            $cart = session()->get('cart', []);
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
                    $totalPrice += $discountedPrice * $item['quantity'];
                 }
            }
        }
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak valid atau stok habis.');
        }
        
        // Ambil data Provinsi untuk Dropdown
        $provinces = $this->rajaOngkirService->getProvinces();

        return view('order.checkout', [
            'title' => 'Checkout Pesanan',
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'user' => Auth::user(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
            'discount' => $activeDiscount,
            'isDirectCheckout' => session('is_direct_checkout', false),
            'branch' => $branch,
            'provinces' => $provinces
        ]);
    }

    // ==========================================
    // 2. PROSES CHECKOUT (SIMPAN ORDER) --> INI YANG HILANG TADI
    // ==========================================
    public function checkout(Request $request)
    {
        $branch = session('selected_branch');
        
        // Validasi Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'proof_image' => 'nullable|image|max:2048', // Jika transfer manual
        ]);

        // Cek Cabang Asal untuk Ongkir
        $originId = self::ORIGIN_WARU; 
        if ($branch && str_contains(strtolower($branch->name), 'bojonegoro')) {
            $originId = self::ORIGIN_BOJONEGORO;
        }

        // --- SIAPKAN DATA ITEM & BERAT ---
        $orderProducts = [];
        $totalAmount = 0;
        $totalWeight = 0;
        
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        // Cek apakah ini Direct Buy atau Cart Buy
        if ($request->has('direct_products')) {
            // DIRECT BUY
            foreach ($request->direct_products as $productId => $qty) {
                $product = Product::find($productId);
                if ($product) {
                    $price = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                    $subtotal = $price * $qty;
                    
                    $orderProducts[$productId] = [
                        'quantity' => $qty, 
                        'price' => $price,
                        'original_price' => $product->price
                    ];
                    
                    $totalAmount += $subtotal;
                    $totalWeight += ($product->weight ?? 1000) * $qty;
                    
                    // Kurangi Stok
                    $product->decrement('stock', $qty);
                }
            }
        } else {
            // CART BUY
            $cart = session()->get('cart', []);
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && $product->branch_id == $branch->id) {
                    $qty = $item['quantity'];
                    $price = $this->calculateDiscountedPrice($product->price, $activeDiscount);
                    $subtotal = $price * $qty;

                    $orderProducts[$productId] = [
                        'quantity' => $qty, 
                        'price' => $price,
                        'original_price' => $product->price
                    ];

                    $totalAmount += $subtotal;
                    $totalWeight += ($product->weight ?? 1000) * $qty;

                    // Kurangi Stok
                    $product->decrement('stock', $qty);
                }
            }
        }

        if (empty($orderProducts)) {
            return back()->with('error', 'Terjadi kesalahan pada produk yang dipilih.');
        }

        // --- HITUNG ONGKIR ---
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $destDistrict = $request->district_id;
            $courier = $request->courier_code;
            $serviceName = $request->courier_service; // Contoh: "REG"

            // Panggil Service RajaOngkir untuk dapatkan harga real-time
            $costs = $this->rajaOngkirService->calculateShippingCost($originId, $destDistrict, max($totalWeight, 1), $courier);
            
            // Cari harga yang cocok dengan layanan yang dipilih user
            foreach($costs as $c) {
                if($c['service'] == $serviceName) {
                    $shippingCost = $c['cost'];
                    break;
                }
            }
            
            // Fallback jika tidak ketemu (ambil yang pertama/termurah)
            if ($shippingCost == 0 && !empty($costs)) {
                $shippingCost = $costs[0]['cost'];
            }
        }

        $totalAmount += $shippingCost;

        // --- SIMPAN KE DATABASE (TRANSACTION) ---
        return DB::transaction(function () use ($request, $branch, $totalAmount, $shippingCost, $orderProducts, $activeDiscount) {
            
            // 1. Buat Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(5)) . '-' . time(),
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

            // 2. Buat Payment
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $proofImage = null;
            if ($request->hasFile('proof_image')) {
                $proofImage = $request->file('proof_image')->store('payments', 'public');
            }

            $payment = $order->payments()->create([
                'payment_number' => 'PAY-' . strtoupper(Str::random(5)),
                'payment_method_id' => $paymentMethod->id,
                'amount' => $totalAmount,
                'payer_name' => $request->customer_name,
                'payer_phone' => $request->customer_phone,
                'proof_image' => $proofImage,
                'status' => 'pending'
            ]);

            // 3. Clear Session Cart / Direct
            session()->forget(['is_direct_checkout', 'direct_checkout_item']);
            if (!$request->has('direct_products')) {
                session()->forget('cart');
            }

            // 4. Handle MIDTRANS (QRIS / Virtual Account)
            if ($paymentMethod->type === 'qris' || $paymentMethod->type === 'va') {
                try {
                    $midtrans = new MidtransService();
                    // Pastikan method createTransaction ada di MidtransService Anda
                    $snapToken = $midtrans->createTransaction($payment); 
                    $payment->update(['snap_token' => $snapToken]);
                    
                    return redirect()->route('payment.pay', $payment->payment_number);
                } catch (\Exception $e) {
                    Log::error("Midtrans Error: " . $e->getMessage());
                    return redirect()->route('pesanan.show', $order->order_number)
                        ->with('error', 'Order dibuat tapi Gagal koneksi Payment Gateway. Hubungi Admin.');
                }
            }

            return redirect()->route('pesanan.show', $order->order_number)
                ->with('success', 'Pesanan berhasil dibuat!');
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
        
        // Tentukan Origin
        $originId = self::ORIGIN_WARU; 
        if ($branch && str_contains(strtolower($branch->name), 'bojonegoro')) {
            $originId = self::ORIGIN_BOJONEGORO;
        }

        // Hitung Berat (Isolasi Direct vs Cart)
        $totalWeight = 0;
        if (session('is_direct_checkout') && session()->has('direct_checkout_item')) {
            $item = session('direct_checkout_item');
            $product = Product::find($item['id']);
            if ($product) {
                $totalWeight = ($product->weight ?? 1000) * $item['quantity'];
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if ($product) $totalWeight += ($product->weight ?? 1000) * $details['quantity'];
            }
        }

        $costs = $this->rajaOngkirService->calculateShippingCost(
            $originId, 
            $request->district_id, 
            max((int)$totalWeight, 1), 
            $request->courier
        );

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
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        
        // DEFINISIKAN STATUS OPTIONS AGAR TIDAK ERROR DI VIEW
        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'payment_check' => 'Cek Pembayaran',
            'processing' => 'Diproses',
            'shipping' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return view('order.index', [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
            'statusOptions' => $statusOptions // Dikirim ke resources/views/order/index.blade.php
        ]);
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
}