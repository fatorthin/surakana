<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoastLogController as AdminRoastLogController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/link', fn() => view('link'))->name('link');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/my/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/my/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('products', AdminProductController::class)->only(['index', 'create', 'edit']);
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('finances', [AdminFinanceController::class, 'index'])->name('finances.index');
        Route::get('roasting-logs', [AdminRoastLogController::class, 'index'])->name('roasting-logs.index');
        Route::post('roasting-logs/session', [AdminRoastLogController::class, 'startSession'])->name('roasting-logs.session.start');
        Route::get('roasting-logs/session', [AdminRoastLogController::class, 'session'])->name('roasting-logs.session');
        Route::delete('roasting-logs/session', [AdminRoastLogController::class, 'cancelSession'])->name('roasting-logs.session.cancel');
        Route::post('roasting-logs', [AdminRoastLogController::class, 'store'])->name('roasting-logs.store');
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::resource('articles', AdminArticleController::class)->only(['index', 'create', 'edit']);
        Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    });

require __DIR__ . '/auth.php';
