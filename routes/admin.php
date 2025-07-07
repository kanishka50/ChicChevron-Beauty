<?php

use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Route;

// Admin guest routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

// Admin authenticated routes
Route::middleware('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');
    
    // Other admin routes will be added in later phases
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () { return 'Products Management'; })->name('index');
    });
    
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', function () { return 'Categories Management'; })->name('index');
    });
    
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', function () { return 'Brands Management'; })->name('index');
    });
});




























// // Admin auth routes
// Route::get('/login', function () {
//     return 'Admin login page';
// })->name('login');

// Route::post('/logout', function () {
//     return redirect('/admin/login');
// })->name('logout');

// // Admin dashboard routes (protected in next phase)
// Route::get('/dashboard', function () {
//     return 'Admin dashboard';
// })->name('dashboard');

// // Placeholder routes for admin panel
// Route::prefix('products')->name('products.')->group(function () {
//     Route::get('/', function () { return 'Products index'; })->name('index');
// });

// Route::prefix('categories')->name('categories.')->group(function () {
//     Route::get('/', function () { return 'Categories index'; })->name('index');
// });

// Route::prefix('brands')->name('brands.')->group(function () {
//     Route::get('/', function () { return 'Brands index'; })->name('index');
// });

// Route::prefix('orders')->name('orders.')->group(function () {
//     Route::get('/', function () { return 'Orders index'; })->name('index');
// });

// Route::prefix('promotions')->name('promotions.')->group(function () {
//     Route::get('/', function () { return 'Promotions index'; })->name('index');
// });

// Route::prefix('complaints')->name('complaints.')->group(function () {
//     Route::get('/', function () { return 'Complaints index'; })->name('index');
// });

// Route::prefix('banners')->name('banners.')->group(function () {
//     Route::get('/', function () { return 'Banners index'; })->name('index');
// });

// Route::prefix('reports')->name('reports.')->group(function () {
//     Route::get('/', function () { return 'Reports index'; })->name('index');
// });

// Route::prefix('content')->name('content.')->group(function () {
//     Route::get('/pages', function () { return 'Content pages'; })->name('pages');
// });