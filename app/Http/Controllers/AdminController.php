<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Rent;
use App\Models\User;
use App\Models\Branch;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\Review;
use App\Models\WhatsappNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\RajaOngkirService;

class AdminController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    // Update hanya function dashboard di AdminController:

    public function dashboard()
{
    $branch = session('selected_branch');
    
    $stats = [
        'total_products' => Product::when($branch, function($q) use ($branch) {
            return $q->where('branch_id', $branch->id);
        })->count(),
        
        'total_categories' => Category::count(),
        
        'total_orders' => Order::when($branch, function($q) use ($branch) {
            return $q->where('branch_id', $branch->id);
        })->count(),
        
        'total_rents' => Rent::when($branch, function($q) use ($branch) {
            return $q->where('branch_id', $branch->id);
        })->count(),
        
        'total_users' => User::where('role', 'user')
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })->count(),
        
        'pending_orders' => Order::where('status', 'pending')
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })->count(),
        
        'active_discount' => Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first(),
    ];

    // Recent orders (max 5)
    $recentOrders = Order::with(['user', 'products'])
        ->when($branch, function($q) use ($branch) {
            return $q->where('branch_id', $branch->id);
        })
        ->latest()
        ->take(5)
        ->get();
        
    // Recent rents (max 5)
    $recentRents = Rent::with(['user', 'products'])
        ->when($branch, function($q) use ($branch) {
            return $q->where('branch_id', $branch->id);
        })
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', [
        'title' => 'Dashboard Admin',
        'stats' => $stats,
        'recentOrders' => $recentOrders,
        'recentRents' => $recentRents,
        'branch' => $branch
    ]);
}
    
    // Products Management
    public function index()
    {
        $branch = session('selected_branch');
        $products = Product::with(['category', 'branch'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.products.index', [
            'title' => 'Manajemen Produk',
            'products' => $products
        ]);
    }
    
    public function create()
    {
        $categories = Category::all();
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.products.create', [
            'title' => 'Tambah Produk',
            'categories' => $categories,
            'branches' => $branches
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_available_for_rent' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Jika branch tidak dipilih, gunakan branch dari session
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch->id;
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        Product::create($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.products.edit', [
            'title' => 'Edit Produk',
            'product' => $product,
            'categories' => $categories,
            'branches' => $branches
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_available_for_rent' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Jika branch tidak dipilih, gunakan branch dari session
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch->id;
        }
        
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = $product->image;
        }
        
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
    
    // Categories Management
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', [
            'title' => 'Manajemen Kategori',
            'categories' => $categories
        ]);
    }
    
    public function createCategory()
    {
        return view('admin.categories.create', [
            'title' => 'Tambah Kategori'
        ]);
    }
    
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', [
            'title' => 'Edit Kategori',
            'category' => $category
        ]);
    }
    
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }
    
    public function destroyCategory(Category $category)
    {
        // Hapus semua produk dalam kategori
        Product::where('category_id', $category->id)->delete();
        $category->delete();
        
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan semua produknya berhasil dihapus.');
    }
    
    // Orders Management
    public function orders()
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
    
    public function createOrder()
    {
        $products = Product::where('is_available', true)->where('stock', '>', 0)->get();
        $provinces = $this->rajaOngkirService->getProvinces();
        return view('admin.orders.create', [
            'products' => $products,
            'provinces' => $provinces
        ]);
    }
    
    public function storeOrder(Request $request)
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
        
        // Hitung shipping cost
        $shippingCost = 0;
        if ($request->delivery_type === 'delivery') {
            $shippingCost = $this->calculateShippingCost($branch, $request->customer_address);
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
    
    public function showOrder(Order $order)
    {
        $statusOptions = Order::getStatusOptions();
        
        return view('admin.orders.show', [
            'title' => 'Detail Pesanan ' . $order->order_number,
            'order' => $order,
            'statusOptions' => $statusOptions,
        ]);
    }
    
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,payment_check,processing,shipping,completed,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->update($validated);
        
        // Jika status berubah ke completed, kirim notifikasi WhatsApp
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $this->sendWhatsappNotification($order->user->phone, 
                "Pesanan {$order->order_number} telah selesai. Silakan berikan review produk.");
        }
        
        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
    
    // Rents Management
    public function rents()
    {
        $branch = session('selected_branch');
        $rents = Rent::with(['user', 'products', 'branch'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->paginate(10);
            
        $statusOptions = Rent::getStatusOptions();
        
        return view('admin.rents.index', [
            'title' => 'Manajemen Penyewaan',
            'rents' => $rents,
            'statusOptions' => $statusOptions,
        ]);
    }
    
    public function showRent(Rent $rent)
    {
        $statusOptions = Rent::getStatusOptions();
        
        return view('admin.rents.show', [
            'title' => 'Detail Sewa ' . $rent->rent_number,
            'rent' => $rent,
            'statusOptions' => $statusOptions,
        ]);
    }
    
    public function updateRentStatus(Request $request, Rent $rent)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,payment_check,confirmed,active,returned,completed,cancelled,overdue'
        ]);
        
        $oldStatus = $rent->status;
        $rent->update($validated);
        
        // Jika status berubah, kirim notifikasi
        if ($validated['status'] !== $oldStatus) {
            $statusLabel = $statusOptions[$validated['status']] ?? $validated['status'];
            $this->sendWhatsappNotification($rent->user->phone, 
                "Status sewa {$rent->rent_number} berubah menjadi: {$statusLabel}");
        }
        
        return redirect()->route('admin.rents.index')->with('success', 'Status sewa berhasil diperbarui.');
    }
    
    // Payments Management
    public function payments()
    {
        $payments = Payment::with(['paymentMethod', 'transaction'])
            ->latest()
            ->paginate(10);
            
        return view('admin.payments.index', [
            'title' => 'Manajemen Pembayaran',
            'payments' => $payments
        ]);
    }
    
    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,success,failed,expired'
        ]);
        
        $payment->update($validated);
        
        // Jika pembayaran sukses, update status order/rent
        if ($validated['status'] === 'success') {
            $transaction = $payment->transaction;
            if ($transaction instanceof Order) {
                $transaction->update(['status' => 'processing']);
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'confirmed']);
            }
        }
        
        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }
    
    // Shipments Management
    public function shipments()
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
                $this->sendWhatsappNotification($transaction->user->phone, 
                    "Pesanan {$transaction->order_number} sudah dalam pengiriman. Silakan konfirmasi saat barang diterima.");
            } elseif ($transaction instanceof Rent) {
                $transaction->update(['status' => 'active']);
                $this->sendWhatsappNotification($transaction->user->phone, 
                    "Sewa {$transaction->rent_number} sudah aktif. Barang sedang dalam perjalanan.");
            }
        }
        
        return redirect()->route('admin.shipments.index')->with('success', 'Status pengiriman berhasil diperbarui.');
    }
    
    // Reviews Management
    public function reviews()
    {
        $reviews = Review::with(['user', 'product', 'order', 'rent'])
            ->latest()
            ->paginate(10);
            
        return view('admin.reviews.index', [
            'title' => 'Manajemen Review',
            'reviews' => $reviews
        ]);
    }
    
    public function approveReview(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil disetujui.');
    }
    
    public function destroyReview(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil dihapus.');
    }
    
    // Raja Ongkir Methods
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
    
    // Helper Methods
    private function calculateShippingCost($branch, $destination)
    {
        // Implementasi logika hitung ongkir
        // Untuk sementara, return fixed cost
        return 20000;
    }
    
    private function sendWhatsappNotification($phoneNumber, $message)
    {
        try {
            // Simpan ke database
            WhatsappNotification::create([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'type' => 'notification',
                'reference_id' => 0,
                'status' => 'pending'
            ]);
            
            // Log
            \Log::info('WhatsApp notification queued: ' . $message);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            return false;
        }
    }
}