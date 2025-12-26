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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Branch Selection Routes
|--------------------------------------------------------------------------
*/
Route::get('/select-branch', [BranchController::class, 'select'])->name('select.branch');
Route::post('/select-branch', [BranchController::class, 'storeSelection'])->name('branch.select');
Route::post('/change-branch', [BranchController::class, 'change'])->name('branch.change');

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Tanpa Login)
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

// Authentication Laravel Starter Kit
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Payment Callback (Webhook Midtrans)
|--------------------------------------------------------------------------
*/
Route::post('/payment/callback', [PaymentCallbackController::class, 'handle'])->name('payment.callback');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'branch.selected'])->group(function () {
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart System
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Order Process
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])->name('checkout.form');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    // Orders History (Pembelian)
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{orderNumber}', [OrderController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{order}/cancel', [OrderController::class, 'cancel'])->name('pesanan.cancel');
    Route::post('/pesanan/{order}/complete', [OrderController::class, 'complete'])->name('pesanan.complete');

    // Cari bagian Rents (User View) dan pastikan seperti ini:
Route::get('/sewa', [RentController::class, 'index'])->name('rent.index');
Route::get('/sewa/create/{product?}', [RentController::class, 'create'])->name('rent.create'); // Hanya gunakan satu nama ini
Route::post('/sewa', [RentController::class, 'store'])->name('rent.store');
Route::get('/sewa/{rentNumber}', [RentController::class, 'show'])->name('rent.show');
Route::post('/sewa/{rent}/cancel', [RentController::class, 'cancel'])->name('rent.cancel');
Route::post('/sewa/{rent}/return', [RentController::class, 'return'])->name('rent.return');

    // Review System
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Payment Processing (User Side)
    Route::post('/payment/process/order/{id}', [PaymentController::class, 'processOrderPayment'])->name('payment.order.process');
    Route::post('/payment/process/rent/{id}', [PaymentController::class, 'processRentPayment'])->name('payment.rent.process');
    Route::get('/payment/pay/{paymentNumber}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

    // --- Dynamic RajaOngkir Routes ---
    // --- Dynamic RajaOngkir Routes ---
// Pastikan semua mengarah ke OrderController agar sinkron dengan perbaikan kita
Route::get('/rajaongkir/provinces', [OrderController::class, 'getProvinces'])->name('rajaongkir.provinces');
Route::get('/rajaongkir/cities/{provinceId}', [OrderController::class, 'getCities'])->name('rajaongkir.cities');
Route::get('/rajaongkir/districts/{cityId}', [OrderController::class, 'getDistricts'])->name('rajaongkir.districts');
Route::get('/rajaongkir/shipping', [OrderController::class, 'getShippingCost'])->name('rajaongkir.shipping');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Product Management Admin
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Category Management Admin
    // Cari bagian ini di web.php dan pastikan seperti ini:
Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/rajaongkir/cities/{provinceId}', [OrderController::class, 'getCities']);
    Route::get('/rajaongkir/districts/{cityId}', [OrderController::class, 'getDistricts']);
    Route::get('/rajaongkir/shipping', [OrderController::class, 'getShippingCost']);

    // Order Management Admin (Pembelian)
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'createAdmin'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'storeAdmin'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'showAdmin'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Rent Management Admin (Penyewaan)
    Route::get('/rents', [RentController::class, 'adminIndex'])->name('rents.index');
    Route::get('/rents/{rent}', [RentController::class, 'showAdmin'])->name('rents.show');
    Route::put('/rents/{rent}/status', [RentController::class, 'updateStatus'])->name('rents.update-status');
    
    // Resource Controllers (Discounts & Branches)
    Route::resource('discounts', DiscountController::class);
    Route::resource('branches', BranchController::class);
    
    // Payment Management Admin
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
    Route::put('/payments/{payment}/status', [PaymentController::class, 'updateStatus'])->name('payments.update-status');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])->name('payments.verify');
    
    // Shipment Management Admin
    Route::get('/shipments', [OrderController::class, 'shipmentsIndex'])->name('shipments.index');
    Route::put('/shipments/{shipment}/status', [OrderController::class, 'updateShipmentStatus'])->name('shipments.update-status');
    
    // Review Approval Admin
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::put('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroyAdmin'])->name('reviews.destroy');

    // Employee Management
    Route::resource('employees', EmployeeController::class);

    // Shift & Attendance Management
    Route::get('shifts/attendance', [ShiftController::class, 'attendance'])->name('shifts.attendance');
    Route::resource('shifts', ShiftController::class);

    Route::get('/rekapan', [ReportController::class, 'index'])->name('reports.index');
});