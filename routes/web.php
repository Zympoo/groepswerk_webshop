<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\ProductCatalog;
use App\Livewire\Backend\Dashboard as AdminDashboard;

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
 * 2. BEVEILIGD KLANTENPORTAAL
 * Routes die alleen toegankelijk zijn voor ingelogde gebruikers. 
 */
Route::middleware(['auth', 'verified'])->group(function () {
    // Het standaard dashboard van de starter kit.
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
});

/**
 * 3. ADMIN BACKEND AREA
 * Toegankelijk via /admin prefix voor beheerdoeleinden.
 */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    });

require __DIR__ . '/settings.php';
