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
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ComplaintController;

// All routes in this file are prefixed with 'admin' and use 'admin.' name prefix
// Only admin users can access these routes

Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh-stats', [AdminDashboardController::class, 'refreshStats'])->name('dashboard.refresh');

     // Homepage cache management
    Route::post('/clear-homepage-cache', [HomeController::class, 'clearCache'])->name('clear-homepage-cache');
    
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

        Route::post('/{product}/combinations', [ProductVariantController::class, 'storeCombination'])->name('variants.combinations.store');
        Route::put('/combinations/{combination}', [ProductVariantController::class, 'updateCombination'])->name('products.combinations.update');
        Route::get('/combinations/{combination}', [ProductVariantController::class, 'getCombination'])->name('products.combinations.show');
        
        // Variant management routes
        Route::get('/{product}/variants', [ProductVariantController::class, 'index'])->name('variants');
        Route::post('/{product}/variants', [ProductVariantController::class, 'store'])->name('variants.store');
        Route::put('/variants/{variant}', [ProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');
        Route::get('/variants/{variant}', [ProductVariantController::class, 'show'])->name('variants.show');
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
        
        // Combination routes
        Route::get('/combinations/{combination}', [InventoryController::class, 'getCombination'])->name('combinations.show');
        Route::put('/combinations/{combination}', [InventoryController::class, 'updateCombination'])->name('combinations.update');
    });

    // =====================================================
    // ORDER MANAGEMENT ROUTES (NEW)
    // =====================================================
    Route::prefix('orders')->name('orders.')->group(function () {
        // Order listing and management
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        
        // Order status management
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/bulk-update', [OrderController::class, 'bulkUpdate'])->name('bulk-update');
        
        // Invoice generation
        Route::get('/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('invoice');
        
        // Order export and statistics
        Route::get('-export', [OrderController::class, 'export'])->name('export');
        Route::get('-statistics', [OrderController::class, 'statistics'])->name('statistics');
        
        // Order search and additional features
        Route::get('-search', [OrderController::class, 'search'])->name('search');
        Route::post('/{order}/add-note', [OrderController::class, 'addNote'])->name('add-note');
        Route::post('/{order}/mark-priority', [OrderController::class, 'markPriority'])->name('mark-priority');
    });


    // =====================================================
        
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
    
    // Texture management
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
    
    // Complaints management
Route::prefix('complaints')->name('complaints.')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('index');
    Route::get('/{complaint}', [ComplaintController::class, 'show'])->name('show');
    Route::post('/{complaint}/respond', [ComplaintController::class, 'respond'])->name('respond');
    Route::patch('/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('update-status');
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