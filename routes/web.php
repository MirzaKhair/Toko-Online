<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Customer\CategoryController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController;
use Illuminate\Support\Facades\Route;

// Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/keranjang', fn () => view('customer.cart.index'))->name('cart.index');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    });
});
