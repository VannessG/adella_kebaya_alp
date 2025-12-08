<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Product;
use App\Models\Branch;
use App\Models\PaymentMethod;
use App\Models\Discount;
use App\Models\WhatsappNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\RajaOngkirService;

class RentController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function index()
    {
        $rents = Rent::where('user_id', Auth::id())->latest()->get();
        $statusOptions = Rent::getStatusOptions();
        
        return view('rent.index', [
            'title' => 'Riwayat Sewa',
            'rents' => $rents,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function show($rentNumber)
    {
        $rent = Rent::where('rent_number', $rentNumber)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                    
        $statusOptions = Rent::getStatusOptions();
        
        return view('rent.show', [
            'title' => 'Detail Sewa ' . $rentNumber,
            'rent' => $rent,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function create(Request $request, Product $product = null)
    {
        $branch = session('selected_branch');
        
        if (!$branch) {
            return redirect()->route('select.branch')->with('error', 'Silakan pilih cabang terlebih dahulu');
        }

        $products = Product::where('branch_id', $branch->id)
                        ->where('is_available_for_rent', true)
                        ->where('stock', '>', 0)
                        ->get();
                        
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
            
        $branches = Branch::where('is_active', true)->get();

        // Jika ada product yang dipilih langsung
        $selectedProduct = null;
        if ($product) {
            $selectedProduct = $product;
            // Pastikan product tersedia untuk disewa
            if (!$selectedProduct->is_available_for_rent || $selectedProduct->stock <= 0) {
                return redirect()->back()->with('error', 'Produk ini tidak tersedia untuk disewa');
            }
        }

        return view('rent.create', [
            'title' => 'Sewa Kebaya',
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'paymentMethods' => $paymentMethods,
            'discount' => $activeDiscount,
            'branch' => $branch,
            'branches' => $branches,
            'isDirectRent' => $product !== null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_type' => 'required|in:pickup,delivery',
            'customer_address' => 'required_if:delivery_type,delivery|string|max:500',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $branch = session('selected_branch');
        $totalAmount = 0;
        $rentProducts = [];
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Validasi tanggal dan stok
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan');
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi');
            }

            if ($totalDays < $product->min_rent_days) {
                return redirect()->back()->with('error', 'Minimal sewa untuk ' . $product->name . ' adalah ' . $product->min_rent_days . ' hari');
            }

            if ($totalDays > $product->max_rent_days) {
                return redirect()->back()->with('error', 'Maksimal sewa untuk ' . $product->name . ' adalah ' . $product->max_rent_days . ' hari');
            }

            $rentPrice = $product->calculateRentPrice($totalDays);
            $subtotal = $rentPrice * $item['quantity'];
            $totalAmount += $subtotal;

            $rentProducts[$product->id] = [
                'quantity' => $item['quantity'],
                'price_per_day' => $product->rent_price_per_day,
                'subtotal' => $subtotal
            ];

            // Kurangi stok sementara
            $product->decrement('stock', $item['quantity']);
        }

        // Hitung biaya pengiriman jika delivery
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            // Logika hitung ongkir (bisa diintegrasikan dengan RajaOngkir)
            $shippingCost = $this->calculateShippingCost($branch, $request->customer_address);
        }

        $totalAmount += $shippingCost;

        // Buat transaksi sewa
        $rent = Rent::create([
            'rent_number' => 'RENT-' . strtoupper(Str::random(3)) . '-' . time(),
            'user_id' => Auth::id(),
            'branch_id' => $branch->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'delivery_type' => $request->delivery_type,
            'shipping_cost' => $shippingCost,
            'customer_address' => $request->customer_address,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
        ]);

        $rent->products()->sync($rentProducts);

        // Buat pembayaran
        $paymentMethod = PaymentMethod::find($request->payment_method_id);
        
        $payment = $rent->payments()->create([
            'payment_number' => 'PAY-' . strtoupper(Str::random(3)) . '-' . time(),
            'payment_method_id' => $paymentMethod->id,
            'amount' => $totalAmount,
            'payer_name' => $request->customer_name,
            'payer_phone' => $request->customer_phone,
            'status' => $paymentMethod->type === 'qris' ? 'pending' : 'processing',
        ]);

        // Kirim notifikasi WhatsApp
        $this->sendWhatsappNotification($rent, $payment);

        return redirect()->route('rent.show', $rent->rent_number)
            ->with('success', 'Pesanan sewa berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'district_id' => 'required|exists:districts,id',
            'courier_code' => 'required|string',
            'courier_service' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
        ]);

        $branch = session('selected_branch');
        $totalAmount = 0;
        $orderProducts = [];
        
        // Hitung total harga produk
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan');
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->back()->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi');
            }

            $orderProducts[$product->id] = [
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];

            $totalAmount += $product->price * $item['quantity'];
        }

        // Ambil district tujuan dari request
        $destinationDistrictId = $request->input('district_id');
        $originDistrictId = $this->rajaOngkirService->getMagelangDistrictId();

        // Hitung total berat produk
        $totalWeight = 0;
        foreach ($orderProducts as $productId => $item) {
            $product = Product::find($productId);
            $totalWeight += ($product->weight ?? 0) * $item['quantity'];
        }

        // Hitung biaya ongkir via RajaOngkirService
        $courierCode = $request->input('courier_code', 'jne');
        $shippingOptions = $this->rajaOngkirService->calculateShippingCost(
            $originDistrictId,
            $destinationDistrictId,
            $totalWeight,
            $courierCode
        );

        // Pilih service yang dipilih user
        $selectedService = $request->input('courier_service', 'REG');
        $shippingCost = 0;
        foreach ($shippingOptions as $option) {
            if ($option['service'] === $selectedService) {
                $shippingCost = $option['cost'];
                break;
            }
        }

        $totalAmount += $shippingCost;

        // Simpan ke database atau proses lebih lanjut

        return redirect()->back()->with('success', 'Checkout berhasil! Total yang harus dibayar: Rp ' . number_format($totalAmount, 0, ',', '.'));
    }

    private function calculateShippingCost($branch, $destination)
    {
        // Implementasi logika hitung ongkir
        // Bisa menggunakan RajaOngkir API
        return 20000; // Contoh biaya tetap
    }

    private function sendWhatsappNotification($rent, $payment)
    {
        // Implementasi WhatsApp API
        $message = "Pesanan Sewa Baru!\n";
        $message .= "No. Sewa: {$rent->rent_number}\n";
        $message .= "Pelanggan: {$rent->customer_name}\n";
        $message .= "Total: Rp " . number_format($rent->total_amount, 0, ',', '.') . "\n";
        $message .= "Metode: {$payment->paymentMethod->name}";
        
        // Simpan notifikasi
        WhatsappNotification::create([
            'phone_number' => config('app.admin_whatsapp'),
            'message' => $message,
            'type' => 'rent',
            'reference_id' => $rent->id,
            'status' => 'pending'
        ]);
    }
}