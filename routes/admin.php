<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// All routes in this file are prefixed with 'admin' and use 'admin.' name prefix
// Only admin users can access these routes

Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh-stats', [AdminDashboardController::class, 'refreshStats'])->name('dashboard.refresh');
    
    // Product management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', function () { 
            return 'Products Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Product'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Product'; 
        })->name('store');
        Route::get('/{product}/edit', function ($product) { 
            return 'Edit Product'; 
        })->name('edit');
        Route::put('/{product}', function ($product) { 
            return 'Update Product'; 
        })->name('update');
        Route::delete('/{product}', function ($product) { 
            return 'Delete Product'; 
        })->name('destroy');

    });
        
    // Promotions management
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', function () { 
            return 'Promotions Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Promotion'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Promotion'; 
        })->name('store');
        Route::get('/{promotion}/edit', function ($promotion) { 
            return 'Edit Promotion'; 
        })->name('edit');
        Route::put('/{promotion}', function ($promotion) { 
            return 'Update Promotion'; 
        })->name('update');
        Route::delete('/{promotion}', function ($promotion) { 
            return 'Delete Promotion'; 
        })->name('destroy');
    });
    
    // Category management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', function () { 
            return 'Categories Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Category'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Category'; 
        })->name('store');
        Route::get('/{category}/edit', function ($category) { 
            return 'Edit Category'; 
        })->name('edit');
        Route::put('/{category}', function ($category) { 
            return 'Update Category'; 
        })->name('update');
        Route::delete('/{category}', function ($category) { 
            return 'Delete Category'; 
        })->name('destroy');
    });
    
    // Brand management
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', function () { 
            return 'Brands Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Brand'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Brand'; 
        })->name('store');
        Route::get('/{brand}/edit', function ($brand) { 
            return 'Edit Brand'; 
        })->name('edit');
        Route::put('/{brand}', function ($brand) { 
            return 'Update Brand'; 
        })->name('update');
        Route::delete('/{brand}', function ($brand) { 
            return 'Delete Brand'; 
        })->name('destroy');
    });
    
    // Order management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', function () { 
            return 'Orders Management'; 
        })->name('index');
        Route::get('/{order}', function ($order) { 
            return 'View Order'; 
        })->name('show');
        Route::put('/{order}/status', function ($order) { 
            return 'Update Order Status'; 
        })->name('update-status');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () { 
            return 'Reports Dashboard'; 
        })->name('index');
        Route::get('/sales', function () { 
            return 'Sales Report'; 
        })->name('sales');
        Route::get('/inventory', function () { 
            return 'Inventory Report'; 
        })->name('inventory');
        Route::get('/customers', function () { 
            return 'Customers Report'; 
        })->name('customers');
    });
    
    // Banners
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', function () { 
            return 'Banners Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Banner'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Banner'; 
        })->name('store');
        Route::get('/{banner}/edit', function ($banner) { 
            return 'Edit Banner'; 
        })->name('edit');
        Route::put('/{banner}', function ($banner) { 
            return 'Update Banner'; 
        })->name('update');
        Route::delete('/{banner}', function ($banner) { 
            return 'Delete Banner'; 
        })->name('destroy');
    });
    
    // Complaints
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', function () { 
            return 'Complaints Management'; 
        })->name('index');
        Route::get('/{complaint}', function ($complaint) { 
            return 'View Complaint'; 
        })->name('show');
        Route::post('/{complaint}/respond', function ($complaint) { 
            return 'Respond to Complaint'; 
        })->name('respond');
    });
    
    // Promotions management
    Route::prefix('promotions')->name('promotions.')->group(function () {
        Route::get('/', function () { 
            return 'Promotions Management'; 
        })->name('index');
        Route::get('/create', function () { 
            return 'Create Promotion'; 
        })->name('create');
        Route::post('/', function () { 
            return 'Store Promotion'; 
        })->name('store');
        Route::get('/{promotion}/edit', function ($promotion) { 
            return 'Edit Promotion'; 
        })->name('edit');
        Route::put('/{promotion}', function ($promotion) { 
            return 'Update Promotion'; 
        })->name('update');
        Route::delete('/{promotion}', function ($promotion) { 
            return 'Delete Promotion'; 
        })->name('destroy');
    });
    
    // Content management
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/pages', function () { 
            return 'Content Pages Management'; 
        })->name('pages');
        Route::get('/faqs', function () { 
            return 'FAQs Management'; 
        })->name('faqs');
    });
});