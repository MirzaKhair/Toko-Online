<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\StoreSettingController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderTrackingController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use Illuminate\Support\Facades\Route;

// Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori', [CustomerCategoryController::class, 'index'])->name('categories.index');
Route::get('/kategori/{slug}', [CustomerCategoryController::class, 'show'])->name('categories.show');
Route::get('/produk/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

// Cart Routes
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('cart.store');
Route::patch('/keranjang/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pesanan/{order_number}/berhasil', [CheckoutController::class, 'success'])->name('orders.success');

// Tracking Routes
Route::get('/lacak-pesanan', [OrderTrackingController::class, 'form'])->name('tracking.form');
Route::post('/lacak-pesanan', [OrderTrackingController::class, 'search'])->name('tracking.search');
Route::get('/lacak-pesanan/{order_number}', [OrderTrackingController::class, 'detail'])->name('tracking.detail');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        // Category CRUD
        Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/kategori/tambah', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/kategori/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/kategori/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product CRUD
        Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
        Route::get('/produk/tambah', [ProductController::class, 'create'])->name('products.create');
        Route::post('/produk', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Variant Management
        Route::get('/produk/{id}/varian', [VariantController::class, 'index'])->name('variants.index');
        Route::post('/produk/{id}/varian/opsi', [VariantController::class, 'storeOption'])->name('variants.storeOption');
        Route::delete('/produk/{id}/varian/opsi/{optionId}', [VariantController::class, 'destroyOption'])->name('variants.destroyOption');
        Route::post('/produk/{id}/varian/opsi/{optionId}/nilai', [VariantController::class, 'storeValue'])->name('variants.storeValue');
        Route::delete('/produk/{id}/varian/nilai/{valueId}', [VariantController::class, 'destroyValue'])->name('variants.destroyValue');
        Route::post('/produk/{id}/varian', [VariantController::class, 'store'])->name('variants.store');
        Route::put('/produk/{id}/varian/{variantId}', [VariantController::class, 'update'])->name('variants.update');
        Route::delete('/produk/{id}/varian/{variantId}', [VariantController::class, 'destroy'])->name('variants.destroy');

        // Order Management
        Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/pesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::patch('/pesanan/{order}/ongkir', [OrderController::class, 'updateShippingCost'])->name('orders.updateShippingCost');

        // Store Settings
        Route::get('/pengaturan', [StoreSettingController::class, 'index'])->name('settings.index');
        Route::patch('/pengaturan', [StoreSettingController::class, 'update'])->name('settings.update');
    });
});
