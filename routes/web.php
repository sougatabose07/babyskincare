<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\CategoryController as FrontCategoryController;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $banners = Banner::where('active', true)->orderBy('sort_order')->get();
    $featuredProducts = Product::with('category')->where('featured', true)->take(8)->get();
    $categories = Category::orderBy('name')->get();

    return view('welcome', compact('banners', 'featuredProducts', 'categories'));
});

// Frontend product and category pages
Route::get('/product/{product:url}', [FrontProductController::class, 'show'])->name('products.show');
Route::get('/category/{category:url}', [FrontCategoryController::class, 'show'])->name('categories.show');
Route::get('/categories', [FrontCategoryController::class, 'index'])->name('categories.index');
Route::get('/products', [FrontProductController::class, 'index'])->name('products.index');

    // Cart and checkout
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/orders/{order}/verify', [App\Http\Controllers\OrderController::class, 'verify'])->name('orders.verify');
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::get('products/data', [App\Http\Controllers\Admin\ProductController::class, 'data'])->name('products.data');
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        Route::resource('banners', App\Http\Controllers\Admin\BannerController::class)->only(['index', 'store', 'destroy']);
    });
});
