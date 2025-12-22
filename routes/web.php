<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Branch Selection Routes (Paling awal)
|--------------------------------------------------------------------------
*/
Route::get('/select-branch', [BranchController::class, 'select'])->name('select.branch');
Route::post('/select-branch', [BranchController::class, 'storeSelection'])->name('branch.select');
Route::post('/change-branch', [BranchController::class, 'change'])->name('branch.change');

/*
|--------------------------------------------------------------------------
| Public Routes (Setelah pilih cabang)
|--------------------------------------------------------------------------
*/
Route::middleware(['branch.selected'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    // Product catalog
    Route::get('/katalog', [ProductController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/detail/{id}', [ProductController::class, 'show'])->name('katalog.show');

    // Categories
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/{id}', [CategoryController::class, 'show'])->name('kategori.show');
});

// Authentication routes
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Payment Callback (Webhook) - WAJIB DI LUAR AUTH & BRANCH
|--------------------------------------------------------------------------
*/
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('payment.callback');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (User Biasa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'branch.selected'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout Form & Process
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    // Orders (User View)
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('pesanan.cancel');
    Route::post('/pesanan/{order}/complete', [OrderController::class, 'complete'])->name('pesanan.complete');

    // Rents (User View)
    Route::get('/sewa', [RentController::class, 'index'])->name('rent.index');
    Route::get('/sewa/create', [RentController::class, 'create'])->name('rent.create');
    Route::get('/sewa/create/{product}', [RentController::class, 'create'])->name('rent.create.product');
    Route::post('/sewa', [RentController::class, 'store'])->name('rent.store');
    Route::get('/sewa/{rentNumber}', [RentController::class, 'show'])->name('rent.show');
    Route::post('/sewa/{rent}/cancel', [RentController::class, 'cancel'])->name('rent.cancel');
    Route::post('/sewa/{rent}/return', [RentController::class, 'return'])->name('rent.return');

    // Reviews Routes
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Payment Process (Manual & Midtrans Page)
    Route::post('/payment/process/order/{id}', [PaymentController::class, 'processOrderPayment'])->name('payment.order.process');
    Route::post('/payment/process/rent/{id}', [PaymentController::class, 'processRentPayment'])->name('payment.rent.process');
    
    // Midtrans Specific Routes
    Route::get('/payment/pay/{paymentNumber}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

    // --- RAJAONGKIR ROUTES (DIPINDAHKAN KE SINI AGAR BISA DIAKSES USER) ---
    Route::get('/rajaongkir/provinces', [OrderController::class, 'getProvinces'])->name('rajaongkir.provinces');
    
    // Route untuk mengambil daftar Kota berdasarkan ID Provinsi
    Route::get('/rajaongkir/cities/{provinceId}', [OrderController::class, 'getCities'])->name('rajaongkir.cities');
    
    // Route untuk mengambil daftar Kecamatan berdasarkan ID Kota
    Route::get('/rajaongkir/districts/{cityId}', [OrderController::class, 'getDistricts'])->name('rajaongkir.districts');
    
    // Route untuk menghitung Ongkir (Ajax POST)
    Route::get('/rajaongkir/shipping', [OrderController::class, 'getShippingCost'])->name('rajaongkir.shipping');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Product Management
        Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        
        // Category Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Order Management
        Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'createAdmin'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'storeAdmin'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'showAdmin'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Rent Management
        Route::get('/rents', [RentController::class, 'adminIndex'])->name('rents.index');
        Route::get('/rents/{rent}', [RentController::class, 'showAdmin'])->name('rents.show');
        Route::put('/rents/{rent}/status', [RentController::class, 'updateStatus'])->name('rents.update-status');
        
        // Discount Management
        Route::resource('discounts', DiscountController::class);
        
        // Branch Management
        Route::resource('branches', BranchController::class);
        
        // Payment Management
        Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
        Route::put('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
        
        // Shipment Management
        Route::get('/shipments', [OrderController::class, 'shipmentsIndex'])->name('shipments.index');
        Route::put('/shipments/{shipment}/status', [OrderController::class, 'updateShipmentStatus'])->name('shipments.update-status');
        
        // Reviews Management
        Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
        Route::put('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroyAdmin'])->name('reviews.destroy');
    });