<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Remove the login routes since we're using unified login
// Only keep admin-specific authenticated routes

// Admin authenticated routes
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh-stats', [AdminDashboardController::class, 'refreshStats'])->name('dashboard.refresh');
    
    // Product management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () { return 'Products Management'; })->name('index');
    });
    
    // Category management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', function () { return 'Categories Management'; })->name('index');
    });
    
    // Brand management
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', function () { return 'Brands Management'; })->name('index');
    });
});