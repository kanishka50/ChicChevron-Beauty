<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\TextureController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\InventoryController;

// All routes in this file are prefixed with 'admin' and use 'admin.' name prefix
// Only admin users can access these routes

Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh-stats', [AdminDashboardController::class, 'refreshStats'])->name('dashboard.refresh');
    
    // Product management
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    Route::post('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
    Route::delete('/images/{image}', [ProductController::class, 'deleteImage'])->name('images.destroy');
    
    // Variant management routes (for later)
    Route::get('/{product}/variants', [ProductVariantController::class, 'index'])->name('variants');
    Route::post('/{product}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
    Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    // Add these to your existing variant routes
    Route::get('/variants/{variant}', [ProductVariantController::class, 'show'])->name('products.variants.show');

});


 // Inventory Management Routes
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
        Route::post('/add-stock', [InventoryController::class, 'addStock'])->name('add-stock');
        Route::post('/adjust-stock', [InventoryController::class, 'adjustStock'])->name('adjust-stock');
        Route::post('/stock-details', [InventoryController::class, 'getStockDetails'])->name('stock-details');
        Route::get('/low-stock-alerts', [InventoryController::class, 'getLowStockAlerts'])->name('low-stock-alerts');
        Route::get('/export', [InventoryController::class, 'exportReport'])->name('export');
        
        // Existing combination routes (already implemented)
        Route::get('/combinations/{combination}', [InventoryController::class, 'getCombination'])->name('combinations.show');
        Route::put('/combinations/{combination}', [InventoryController::class, 'updateCombination'])->name('combinations.update');
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
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::post('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
});
    
    // Brand management
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('/create', [BrandController::class, 'create'])->name('create');
    Route::post('/', [BrandController::class, 'store'])->name('store');
    Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('edit');
    Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
    Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
    Route::post('/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggle-status');
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


    // Texture management (simple)
Route::prefix('textures')->name('textures.')->group(function () {
    Route::get('/', [TextureController::class, 'index'])->name('index');
    Route::post('/', [TextureController::class, 'store'])->name('store');
    Route::put('/{texture}', [TextureController::class, 'update'])->name('update');
    Route::delete('/{texture}', [TextureController::class, 'destroy'])->name('destroy');
});


// Color management
Route::prefix('colors')->name('colors.')->group(function () {
    Route::get('/', [ColorController::class, 'index'])->name('index');
    Route::post('/', [ColorController::class, 'store'])->name('store');
    Route::put('/{color}', [ColorController::class, 'update'])->name('update');
    Route::delete('/{color}', [ColorController::class, 'destroy'])->name('destroy');
});




});