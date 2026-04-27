<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Livewire\Frontend\ProductCatalog;
use App\Livewire\Backend\Dashboard as AdminDashboard;
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
Route::get('/', function () {
    return redirect()->route('products.index');
})->name('home');

// Route voor de productcatalogus met realtime filtering.
Route::get('/products', ProductCatalog::class)->name('products.index');

/**
 * 2. BEVEILIGD DASHBOARD & ADMIN BACKEND
 * Alle beheer- en dashboard-routes vallen onder het /dashboard prefix.
 */
Route::prefix('dashboard')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {
        Route::livewire('/', AdminDashboard::class)->name('dashboard');
        Route::livewire('/productbeheer', \App\Livewire\Pages\Admin\ProductIndex::class)->name('products.index');
    });

// Hier komen later de subpagina's voor beheer (bijv. productbeheer)

require __DIR__ . '/settings.php';