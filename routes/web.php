<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Public routes (accessible by everyone)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public shop routes

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
// Product variant details for AJAX
Route::get('/products/{product}/variant-details', [ProductController::class, 'getVariantDetails'])->name('products.variant-details');


// Search functionality (Replace existing search route)
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/ingredients', [SearchController::class, 'ingredients'])->name('search.ingredients');
Route::get('/search/trending', [SearchController::class, 'trending'])->name('search.trending');


Route::get('/categories', function () {
    $categories = \App\Models\Category::active()
        ->ordered()
        ->whereHas('products', function ($query) {
            $query->active();
        })
        ->withCount(['products' => function ($query) {
            $query->active();
        }])
        ->get();
    
    return view('categories.index', compact('categories'));
})->name('categories.index');

Route::get('/categories/{category:slug}', function (\App\Models\Category $category) {
    return redirect()->route('products.index', ['category' => $category->id]);
})->name('categories.show');

// Brand browsing
Route::get('/brands', function () {
    $brands = \App\Models\Brand::active()
        ->whereHas('products', function ($query) {
            $query->active();
        })
        ->withCount(['products' => function ($query) {
            $query->active();
        }])
        ->orderBy('name')
        ->get();
    
    return view('brands.index', compact('brands'));
})->name('brands.index');

Route::get('/brands/{brand:slug}', function (\App\Models\Brand $brand) {
    return redirect()->route('products.index', ['brands' => [$brand->id]]);
})->name('brands.show');




Route::get('/about', function () {
    return view('welcome');
})->name('about');

Route::get('/contact', function () {
    return view('welcome');
})->name('contact');

Route::get('/faq', function () {
    return view('welcome');
})->name('faq');

Route::get('/terms', function () {
    return view('welcome');
})->name('terms');

Route::get('/privacy', function () {
    return view('welcome');
})->name('privacy');

// Guest only routes (only for non-logged-in users)
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    
    // Login (unified for both users and admins)
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    
    // Password Reset
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Authenticated routes (for logged-in users only - both regular and admin)
Route::middleware('auth:web')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Email Verification
    Route::get('verify-email', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    
    // Verified user routes
    Route::middleware('verified')->group(function () {
        Route::get('/account', function () {
            return 'My Account';
        })->name('account.index');
        
        // =====================================================
        // CUSTOMER ORDER MANAGEMENT ROUTES (NEW)
        // =====================================================
        
        // Customer order history and details
        Route::get('/my-orders', [OrderController::class, 'index'])->name('user.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
        
        // Order actions
        Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('user.orders.invoice');
        Route::post('/orders/{order}/complete', [OrderController::class, 'markComplete'])->name('user.orders.complete');
        Route::post('/orders/{order}/request-cancellation', [OrderController::class, 'requestCancellation'])->name('user.orders.request-cancellation');
        Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('user.orders.reorder');
        
        // Order tracking
        Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('user.orders.track');
        
        // Order statistics for dashboard
        Route::get('/my-order-statistics', [OrderController::class, 'getOrderStatistics'])->name('user.orders.statistics');
        
        // =====================================================
        
        // Legacy order route (keeping for backward compatibility)
        Route::get('/orders', function () {
            return redirect()->route('user.orders.index');
        })->name('orders.index');
        
        Route::get('/complaints/create', function () {
            return 'Create Complaint';
        })->name('complaints.create');
    });
});



// =====================================================
// CART ROUTES
// =====================================================
Route::prefix('cart')->name('cart.')->group(function () {
    // Display cart
    Route::get('/', [CartController::class, 'index'])->name('index');
    
    // Add to cart (AJAX)
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    
    // Update cart item quantity (AJAX)
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('update-quantity');
    
    // Remove cart item (AJAX)
    Route::post('/remove', [CartController::class, 'removeItem'])->name('remove');
    
    // Clear entire cart (AJAX)
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
    
    // Get cart count for header (AJAX)
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    
    // Get cart summary for dropdown (AJAX)
    Route::get('/summary', [CartController::class, 'getCartSummary'])->name('summary');
    
    // Promotion code handling (AJAX)
    Route::post('/apply-promotion', [CartController::class, 'applyPromotion'])->name('apply-promotion');
    Route::post('/remove-promotion', [CartController::class, 'removePromotion'])->name('remove-promotion');
});

// =====================================================
// CHECKOUT ROUTES (NEW)
// =====================================================
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/{order}/payment', [CheckoutController::class, 'payment'])->name('payment');
    Route::get('/{order}/success', [CheckoutController::class, 'success'])->name('success');
});

// =====================================================
// WISHLIST ROUTES
// =====================================================
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [App\Http\Controllers\WishlistController::class, 'index'])->name('index');
    Route::post('/add', [App\Http\Controllers\WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [App\Http\Controllers\WishlistController::class, 'remove'])->name('remove');
    Route::post('/clear', [App\Http\Controllers\WishlistController::class, 'clear'])->name('clear');
    Route::get('/count', [App\Http\Controllers\WishlistController::class, 'getCount'])->name('count');
    Route::post('/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('check');
});




// =====================================================
// GUEST ORDER TRACKING ROUTES (NEW)
// =====================================================

// Guest order tracking (for orders placed without account)
Route::get('/track-order', function () {
    return view('orders.track-guest');
})->name('orders.track-guest');

Route::post('/track-order', function (Request $request) {
    $request->validate([
        'order_number' => 'required|string',
        'email' => 'required|email'
    ]);
    
    $order = \App\Models\Order::where('order_number', $request->order_number)
                              ->whereHas('user', function ($query) use ($request) {
                                  $query->where('email', $request->email);
                              })
                              ->first();
    
    if (!$order) {
        return back()->withErrors(['order_number' => 'Order not found with provided details.']);
    }
    
    return redirect()->route('orders.track-guest-result', $order);
})->name('orders.track-guest.submit');

Route::get('/track-order/{order}', function (\App\Models\Order $order) {
    // Simple guest tracking page - you can expand this
    $order->load(['items.product', 'statusHistory']);
    return view('orders.track-guest-result', compact('order'));
})->name('orders.track-guest-result');

// =====================================================
// WEBHOOK ROUTES (NEW)
// =====================================================

// PayHere webhook for order status updates
Route::post('/webhooks/payhere', function (Request $request) {
    // PayHere webhook handler
    // This will be implemented when payment system is integrated
    Log::info('PayHere webhook received', $request->all());
    
    // Verify webhook signature
    // Update order status based on payment status
    // Send confirmation emails
    
    return response('OK', 200);
})->name('webhooks.payhere');

// Other payment gateway webhooks can be added here
Route::post('/webhooks/stripe', function (Request $request) {
    // Stripe webhook handler (if implemented in future)
    return response('OK', 200);
})->name('webhooks.stripe');

// =====================================================
// REDIRECT ROUTES (for backward compatibility)
// =====================================================

// Redirect old order URLs to new structure
Route::redirect('/my-account/orders', '/my-orders', 301);
Route::redirect('/account/orders', '/my-orders', 301);
Route::redirect('/user/orders', '/my-orders', 301);

// =====================================================
// API ROUTES FOR ORDER MANAGEMENT (OPTIONAL)
// =====================================================

// API routes for mobile app or AJAX requests
Route::prefix('api/v1')->group(function () {
    
    // Public API endpoints (NO auth required)
    Route::get('/products/{product}/variants', [ProductController::class, 'getVariantDetails'])->name('api.products.variants');
    Route::get('/search/autocomplete', [SearchController::class, 'suggestions'])->name('api.search.autocomplete');
    
    // Authenticated API endpoints
    Route::middleware(['auth:sanctum'])->group(function () {
        // Customer API endpoints
        Route::get('/orders', [OrderController::class, 'index'])->name('api.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');
        Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('api.orders.track');
        Route::post('/orders/{order}/complete', [OrderController::class, 'markComplete'])->name('api.orders.complete');
        
        
    });
});

// =====================================================
// ROUTE MODEL BINDING CUSTOMIZATION (NEW)
// =====================================================

// Custom route model binding for orders to ensure proper authorization
Route::bind('order', function ($value) {
    $order = \App\Models\Order::findOrFail($value);
    
    // If this is an admin route, no additional checks needed
    if (request()->is('admin/*')) {
        return $order;
    }
    
    // For user routes, ensure the user owns the order
    if (Auth::check() && Auth::id() === $order->user_id) {
        return $order;
    }
    
    // For guest tracking, we'll handle authorization in the controller
    if (request()->is('track-order/*')) {
        return $order;
    }
    
    abort(403, 'Unauthorized access to this order.');
});


// Enhanced route model binding for products to handle view tracking
Route::bind('product', function ($value, $route) {
    $product = \App\Models\Product::where('slug', $value)
        ->orWhere('id', $value)
        ->firstOrFail();
    
    // Only increment views for product show pages, not API calls
    if ($route->getName() === 'products.show') {
        $product->increment('views_count');
    }
    
    return $product;
});




// Checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{orderNumber}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{orderNumber}', [CheckoutController::class, 'processPayment'])->name('checkout.payment.process');
    Route::get('/checkout/payment-callback/{orderNumber}', [CheckoutController::class, 'paymentCallback'])->name('checkout.payment.callback');
    Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failed/{orderNumber}', [CheckoutController::class, 'failed'])->name('checkout.failed');
});

// Payment notification routes (no auth required for webhooks)
// Route::post('/payment/notify/payhere', [PaymentNotificationController::class, 'payhere'])->name('payment.notify.payhere');
// Route::post('/payment/notify/stripe', [PaymentNotificationController::class, 'stripe'])->name('payment.notify.stripe');