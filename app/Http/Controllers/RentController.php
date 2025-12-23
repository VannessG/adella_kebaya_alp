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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\RajaOngkirService;
use Carbon\Carbon;

class RentController extends Controller
{
    protected $rajaOngkirService;

    // ID Kecamatan Cabang (Sesuai database RajaOngkir)
    const ORIGIN_WARU = 6626;       
    const ORIGIN_BOJONEGORO = 953;  

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    private function getStatusOptions()
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'payment_check' => 'Cek Pembayaran',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Sedang Disewa',
            'returned' => 'Sudah Dikembalikan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'overdue' => 'Terlambat'
        ];
    }

    public function index()
    {
        $rents = Rent::where('user_id', Auth::id())->latest()->get();
        return view('rent.index', [
            'title' => 'Riwayat Sewa',
            'rents' => $rents,
            'statusOptions' => $this->getStatusOptions(),
        ]);
    }

    public function show($rentNumber)
    {
        $rent = Rent::where('rent_number', $rentNumber)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                    
        return view('rent.show', [
            'title' => 'Detail Sewa ' . $rentNumber,
            'rent' => $rent,
            'statusOptions' => $this->getStatusOptions(),
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
            
        // Ambil data Provinsi untuk Dropdown Alamat
        $provinces = $this->rajaOngkirService->getProvinces();

        $selectedProduct = $product;
        if ($selectedProduct && (!$selectedProduct->is_available_for_rent || $selectedProduct->stock <= 0)) {
            return redirect()->back()->with('error', 'Produk tidak tersedia untuk disewa');
        }

        return view('rent.create', [
            'title' => 'Sewa Kebaya',
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'paymentMethods' => $paymentMethods,
            'discount' => $activeDiscount,
            'branch' => $branch,
            'provinces' => $provinces,
            'isDirectRent' => $product !== null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'district_id' => 'required_if:delivery_type,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $branch = session('selected_branch');
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate);
        if($totalDays < 1) $totalDays = 1;

        $totalAmount = 0;
        $totalWeight = 0;
        $rentProducts = [];

        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                return back()->with('error', 'Stok tidak mencukupi untuk ' . ($product->name ?? 'produk'));
            }

            // Hitung Harga: (Harga/Hari * Durasi) * Quantity
            $rentPricePerItem = $product->rent_price_per_day * $totalDays;
            $subtotal = $rentPricePerItem * $item['quantity'];
            
            $totalAmount += $subtotal;
            $totalWeight += ($product->weight ?? 1000) * $item['quantity'];

            $rentProducts[$product->id] = [
                'quantity' => $item['quantity'],
                'price_per_day' => $product->rent_price_per_day,
                'subtotal' => $subtotal
            ];

            $product->decrement('stock', $item['quantity']);
        }

        // Hitung Ongkir
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $originId = str_contains(strtolower($branch->name), 'bojonegoro') ? self::ORIGIN_BOJONEGORO : self::ORIGIN_WARU;
            
            $costs = $this->rajaOngkirService->calculateShippingCost(
                $originId, 
                $request->district_id, 
                $totalWeight, 
                $request->courier_code ?? 'jne'
            );

            // Cari service yang dipilih (default service pertama)
            $shippingCost = $costs[0]['cost'] ?? 20000;
            foreach($costs as $c) {
                if($c['service'] == $request->courier_service) {
                    $shippingCost = $c['cost'];
                    break;
                }
            }
        }

        $totalAmount += $shippingCost;

        return DB::transaction(function () use ($request, $branch, $startDate, $endDate, $totalDays, $totalAmount, $shippingCost, $rentProducts) {
            $rent = Rent::create([
                'rent_number' => 'RENT-' . strtoupper(Str::random(4)) . '-' . time(),
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

            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $payment = $rent->payments()->create([
                'payment_number' => 'PAY-RENT-' . time(),
                'payment_method_id' => $paymentMethod->id,
                'amount' => $totalAmount,
                'payer_name' => $request->customer_name,
                'payer_phone' => $request->customer_phone,
                'status' => 'pending',
            ]);

            $this->sendWhatsappNotification($rent, $payment);

            return redirect()->route('rent.show', $rent->rent_number)
                ->with('success', 'Penyewaan berhasil dibuat!');
        });
    }

    private function sendWhatsappNotification($rent, $payment)
    {
        $message = "📢 *SEWA BARU* 📢\nNo: {$rent->rent_number}\nPelanggan: {$rent->customer_name}\nTotal: Rp " . number_format($rent->total_amount, 0, ',', '.');
        
        WhatsappNotification::create([
            'phone_number' => config('app.admin_whatsapp', '628123456789'),
            'message' => $message,
            'type' => 'rent',
            'reference_id' => $rent->id,
            'status' => 'pending'
        ]);
    }

    // Admin Methods
    public function adminIndex()
    {
        $branch = session('selected_branch');
        $rents = Rent::with(['user', 'products', 'branch'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.rents.index', [
            'title' => 'Manajemen Penyewaan',
            'rents' => $rents,
            'statusOptions' => $this->getStatusOptions(),
        ]);
    }
}