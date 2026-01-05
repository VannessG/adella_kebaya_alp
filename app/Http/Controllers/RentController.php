<?php

namespace App\Http\Controllers;

use App\Models\Rent;
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
use Carbon\Carbon;

class RentController extends Controller{
    protected $rajaOngkirService;

    const ORIGIN_WARU = 6626;       
    const ORIGIN_BOJONEGORO = 953;  

    public function __construct(RajaOngkirService $rajaOngkirService){
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function cancel(Rent $rent){
        if ($rent->user_id !== Auth::id()) return back()->with('error', 'Akses ditolak.');
        if (!$rent->canBeCancelled()) return back()->with('error', 'Sewa tidak bisa dibatalkan.');

        DB::transaction(function () use ($rent) {
            foreach ($rent->products as $product) { $product->increment('stock', $product->pivot->quantity); }
            $rent->update(['status' => 'cancelled']);
            $rent->payments()->update(['status' => 'failed']);
        });
        return back()->with('success', 'Penyewaan dibatalkan.');
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

    public function getShippingCost(Request $request) {
        $branch = session('selected_branch');
        $originId = str_contains(strtolower($branch->name), 'bojonegoro') ? self::ORIGIN_BOJONEGORO : self::ORIGIN_WARU;
        $costs = $this->rajaOngkirService->calculateShippingCost($originId, $request->district_id, $request->weight ?? 1000, $request->courier, $request->city_id);
        return response()->json(['costs' => $costs]);
    }

    public function index() {
        $rents = Rent::with(['products', 'reviews'])->where('user_id', Auth::id())->latest()->get();
        return view('rent.index', ['rents' => $rents, 'statusOptions' => Rent::getStatusOptions()]);
    }

    public function create(Request $request, Product $product = null) {
        $branch = session('selected_branch');
        if (!$branch) return redirect()->route('select.branch');
        $products = Product::where('branch_id', $branch->id)->where('is_available_for_rent', true)->where('stock', '>', 0)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $discounts = Discount::where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->get();
        $provinces = $this->rajaOngkirService->getProvinces();
        return view('rent.create', compact('products', 'product', 'paymentMethods', 'discounts', 'branch', 'provinces'));
    }

    public function store(Request $request) {
        $request->validate([
            'products' => 'required|array',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'discount_id' => 'nullable|exists:discounts,id',
            'district_id' => 'required_if:delivery_type,delivery',
            'shipping_cost' => 'nullable|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            $branch = session('selected_branch');
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $totalDays = max(1, $startDate->diffInDays($endDate));

            $totalProdukHari = 0; 
            $rentProducts = [];
        
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                $subtotalItem = ($product->rent_price_per_day * $totalDays) * $item['quantity'];
                $totalProdukHari += $subtotalItem; 
                
                $rentProducts[$product->id] = [
                    'quantity' => $item['quantity'], 
                    'price_per_day' => $product->rent_price_per_day, 
                    'subtotal' => $subtotalItem
                ];
                $product->decrement('stock', $item['quantity']);
            }

            $discountAmount = 0;
            if ($request->filled('discount_id')) {
                $appliedDiscount = Discount::find($request->discount_id);
                if ($appliedDiscount && $appliedDiscount->isActive()) {
                    $discountAmount = $appliedDiscount->applyTo($totalProdukHari);
                    $appliedDiscount->increment('used_count');
                }
            }

            $shippingCost = ($request->delivery_type === 'delivery') ? (int)$request->shipping_cost : 0;
            $finalTotal = ($totalProdukHari - $discountAmount) + $shippingCost;

            $rent = Rent::create([
                'rent_number' => 'RENT-' . strtoupper(Str::random(5)) . '-' . time(),
                'user_id' => Auth::id(), 
                'branch_id' => $branch->id,
                'start_date' => $startDate, 
                'end_date' => $endDate, 
                'total_days' => $totalDays,
                'status' => 'pending', 
                'subtotal' => $totalProdukHari, // HARUS DIISI
                'discount_amount' => $discountAmount, // HARUS DIISI
                'total_amount' => $finalTotal,
                'delivery_type' => $request->delivery_type, 
                'shipping_cost' => $shippingCost,
                'customer_address' => $request->customer_address, 
                'customer_name' => $request->customer_name, 
                'customer_phone' => $request->customer_phone,
            ]);
            $rent->products()->sync($rentProducts);

            $appliedDiscount = null; 
            if ($request->filled('discount_id')) {
                $appliedDiscount = Discount::find($request->discount_id);
            }

            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $payment = $rent->payments()->create([
                'payment_number' => 'PAY-RENT-' . strtoupper(Str::random(5)),
                'payment_method_id' => $paymentMethod->id, 
                'amount' => $finalTotal, 
                'payer_name' => $request->customer_name, 
                'payer_phone' => $request->customer_phone, 
                'status' => 'pending'
            ]);

            if (in_array($paymentMethod->type, ['qris', 'va', 'transfer'])) {
                try {
                    $midtrans = new MidtransService();
                    $snapToken = $midtrans->createTransaction($payment);
                    $payment->update(['snap_token' => $snapToken]);
                    
                    if ($paymentMethod->type === 'qris') {
                        return redirect()->route('payment.pay', $payment->payment_number);
                    }
                } catch (\Exception $e) { 
                    Log::error("Midtrans Rent Error: " . $e->getMessage()); 
                }
            }
            return redirect()->route('rent.show', $rent->rent_number)->with('success', 'Penyewaan berhasil dibuat!');
        });
    }

    public function show($rentNumber) {
        $rent = Rent::with(['products', 'payment.paymentMethod', 'branch'])
            ->where('rent_number', $rentNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        return view('rent.show', [
            'rent' => $rent, 
            'statusOptions' => Rent::getStatusOptions(),
            'paymentMethods' => $paymentMethods
        ]);
    }

    public function adminIndex() {
        $query = Rent::with(['user', 'branch'])->latest();

        if (session()->has('selected_branch')) {
            $query->where('branch_id', session('selected_branch')->id);
        } elseif (session()->has('branch_id')) {
            $query->where('branch_id', session('branch_id'));
        }

        $rents = $query->paginate(10);
        
        return view('admin.rents.index', [
            'rents' => $rents, 
            'statusOptions' => Rent::getStatusOptions()
        ]);
    }

    public function showAdmin(Rent $rent) {
        return view('admin.rents.show', [
            'rent' => $rent->load(['products', 'user', 'payments.paymentMethod']),
            'statusOptions' => Rent::getStatusOptions()
        ]);
    }

    public function updateStatus(Request $request, Rent $rent) {
        $request->validate(['status' => 'required|in:' . implode(',', array_keys(Rent::getStatusOptions()))]);
        $rent->update(['status' => $request->status]);
        return back()->with('success', 'Status sewa berhasil diperbarui.');
    }
}