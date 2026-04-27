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
Route::redirect('/', '/products');

// Route voor de productcatalogus met realtime filtering.
Route::livewire('/products', 'pages::products.index');

/**
 * 2. BEVEILIGD DASHBOARD & ADMIN BACKEND
 * Alle beheer- en dashboard-routes vallen onder het /dashboard prefix.
 */
Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {
        Route::livewire('/', AdminDashboard::class)->name('index');
        Route::livewire('/products', \App\Livewire\Pages\Admin\ProductIndex::class)->name('products.index');
        Route::livewire('/categories', \App\Livewire\Pages\Admin\CategoryIndex::class)->name('categories.index');
        Route::livewire('/categories/create', \App\Livewire\Pages\Admin\CategoryUpsert::class)->name('categories.create');
        Route::livewire('/categories/{category}/edit', \App\Livewire\Pages\Admin\CategoryUpsert::class)->name('categories.edit');
    });

// Hier komen later de subpagina's voor beheer (bijv. productbeheer)

require __DIR__ . '/settings.php';
