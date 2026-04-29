<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CheckoutController;
use App\Livewire\Backend\Dashboard as AdminDashboard;
use App\Livewire\Pages\Admin\CategoryIndex;
use App\Livewire\Pages\Admin\CategoryUpsert;
use App\Livewire\Pages\Admin\OrderDetail;
use App\Livewire\Pages\Admin\OrderIndex;
use App\Livewire\Pages\Admin\ProductIndex;
use App\Livewire\Pages\Admin\ProductUpsert;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}', [SocialiteController::class, 'redirect'])
    ->where('provider', 'google|github')
    ->name('social.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->where('provider', 'google|github')
    ->name('social.callback');

/**
 * 1. PUBLIEKE FRONTEND
 * Bezoekers worden automatisch naar de productcatalogus geleid.
 */
Route::redirect('/', '/products');

// Route voor de productcatalogus met realtime filtering.
Route::livewire('/products', 'pages::products.index')->name('home');
Route::livewire('/products/{product:slug}', 'pages::products.show');

Route::livewire('/cart', 'pages::cart.overview');

Route::livewire('/orders', 'pages::order.index');
Route::livewire('/orders/{order}', 'pages::order.show');
Route::post('/orders/{order}/pay', [CheckoutController::class, 'pay'])
    ->name('orders.pay');

Route::livewire('/checkout', 'pages::checkout.index');
Route::get('/checkout/success', [CheckoutController::class, 'success'])
    ->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])
    ->name('checkout.cancel');
/**
 * 2. BEVEILIGD DASHBOARD & ADMIN BACKEND
 * Alle beheer- en dashboard-routes vallen onder het /dashboard prefix.
 */
Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {
        Route::livewire('/', AdminDashboard::class)->name('index');
        Route::livewire('/products', ProductIndex::class)->name('products.index');
        Route::livewire('/products/create', ProductUpsert::class)->name('products.create');
        Route::livewire('/products/{product}/edit', ProductUpsert::class)->name('products.edit');
        Route::livewire('/categories', CategoryIndex::class)->name('categories.index');
        Route::livewire('/categories/create', CategoryUpsert::class)->name('categories.create');
        Route::livewire('/categories/{category}/edit', CategoryUpsert::class)->name('categories.edit');
        Route::livewire('/orders', OrderIndex::class)->name('orders.index');
        Route::livewire('/orders/{order}', OrderDetail::class)->name('orders.show');
    });

// Hier komen later de subpagina's voor beheer (bijv. productbeheer)

require __DIR__.'/settings.php';
