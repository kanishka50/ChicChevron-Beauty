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

// Public routes (accessible by everyone)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public shop routes
Route::get('/products', function () {
    return view('welcome');
})->name('products.index');

Route::get('/categories', function () {
    return view('welcome');
})->name('categories.index');

Route::get('/categories/{category}', function ($category) {
    return view('welcome');
})->name('categories.show');

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

Route::get('/search', function () {
    return view('welcome');
})->name('search');

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
    
    // Wishlist
    Route::get('/wishlist', function () {
        return view('welcome');
    })->name('wishlist.index');
    
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

// Cart route (accessible by both guests and authenticated users)
Route::get('/cart', function () {
    return 'Shopping Cart';
})->name('cart.index');

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
Route::prefix('api/v1')->middleware(['auth:sanctum'])->group(function () {
    
    // Customer API endpoints
    Route::get('/orders', [OrderController::class, 'index'])->name('api.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');
    Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('api.orders.track');
    Route::post('/orders/{order}/complete', [OrderController::class, 'markComplete'])->name('api.orders.complete');
    
    // Admin API endpoints
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('api.admin.orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('api.admin.orders.show');
        Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('api.admin.orders.update-status');
        Route::get('/orders-statistics', [\App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('api.admin.orders.statistics');
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