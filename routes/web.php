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
| Authenticated User Routes
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

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    // Orders
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('pesanan.cancel');
    Route::post('/pesanan/{order}/complete', [OrderController::class, 'complete'])->name('pesanan.complete');

    // Rents
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
        Route::resource('products', AdminController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        
        // Category Management
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        // Order Management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
        Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
        
        // Rent Management
        Route::get('/rents', [AdminController::class, 'rents'])->name('rents.index');
        Route::get('/rents/{rent}', [AdminController::class, 'showRent'])->name('rents.show');
        Route::put('/rents/{rent}/status', [AdminController::class, 'updateRentStatus'])->name('rents.update-status');
        
        // Discount Management
        Route::resource('discounts', DiscountController::class);
        
        // Branch Management
        Route::resource('branches', BranchController::class);
        
        // Payment Management
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::put('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('payments.update-status');
        
        // Shipment Management
        Route::get('/shipments', [AdminController::class, 'shipments'])->name('shipments.index');
        Route::put('/shipments/{shipment}/status', [AdminController::class, 'updateShipmentStatus'])->name('shipments.update-status');
        
        // Reviews Management
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
        Route::put('/reviews/{review}/approve', [AdminController::class, 'approveReview'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');

        // RajaOngkir Routes
        Route::get('/rajaongkir/provinces', [AdminController::class, 'getProvinces'])->name('rajaongkir.provinces');
        Route::get('/rajaongkir/cities/{provinceId}', [AdminController::class, 'getCities'])->name('admin.rajaongkir.cities');
        Route::get('/rajaongkir/districts/{cityId}', [AdminController::class, 'getDistricts'])->name('admin.rajaongkir.districts');
        Route::get('/rajaongkir/shipping', [AdminController::class, 'getShippingCost'])->name('admin.rajaongkir.shipping');
    });