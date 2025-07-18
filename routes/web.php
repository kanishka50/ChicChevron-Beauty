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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ReviewController;

// Public routes (accessible by everyone)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public shop routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
// Variant-specific API endpoints for AJAX
Route::get('/api/products/{product}/variants', [ProductController::class, 'getVariants'])->name('api.products.variants');
Route::get('/api/variants/{variant}', [ProductController::class, 'getVariantDetails'])->name('api.variants.details');


// Search functionality
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/ingredients', [SearchController::class, 'ingredients'])->name('search.ingredients');
Route::get('/search/trending', [SearchController::class, 'trending'])->name('search.trending');

// Categories
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

Route::get('/category/{category:slug}', [ProductController::class, 'categoryProducts'])->name('category.products');

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

// Static pages
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

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

// Email verification routes
// For development with ngrok, temporarily remove 'signed' middleware
Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['throttle:6,1'])  // Removed 'signed' middleware for development
    ->name('verification.verify');

// For production, use this instead:
/*
Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
*/
// Authenticated routes
Route::middleware('auth:web')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    Route::get('verify-email', [VerificationController::class, 'show'])->name('verification.notice');
    
    Route::post('email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    // Verified user routes
    Route::middleware('verified')->group(function () {
        
        
        // Customer order management
        Route::get('/my-orders', [OrderController::class, 'index'])->name('user.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('user.orders.invoice');
        Route::post('/orders/{order}/complete', [OrderController::class, 'markComplete'])->name('user.orders.complete');
        Route::post('/orders/{order}/request-cancellation', [OrderController::class, 'requestCancellation'])->name('user.orders.request-cancellation');
        Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('user.orders.track');
        Route::get('/my-order-statistics', [OrderController::class, 'getOrderStatistics'])->name('user.orders.statistics');

    });
});



// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'addToCart'])->name('add');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('update-quantity');
    Route::post('/remove', [CartController::class, 'removeItem'])->name('remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    Route::get('/summary', [CartController::class, 'getCartSummary'])->name('summary');
    Route::post('/apply-promotion', [CartController::class, 'applyPromotion'])->name('apply-promotion');
    Route::post('/remove-promotion', [CartController::class, 'removePromotion'])->name('remove-promotion');
});

// Checkout routes
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/{order}/payment', [CheckoutController::class, 'payment'])->name('payment');
    Route::get('/{order}/success', [CheckoutController::class, 'success'])->name('success');
});

// Payment routes
Route::prefix('checkout/payment')->name('checkout.payment.')->group(function () {
    Route::get('/{order}/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/{order}/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/{order}/status', [PaymentController::class, 'checkStatus'])->name('status');
    Route::post('/{order}/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm')->middleware('auth');
});

// Webhook routes (no auth required)
Route::post('/webhooks/payhere', [PaymentController::class, 'webhook'])->name('webhooks.payhere');
Route::match(['GET', 'POST'], '/webhooks/payhere/debug', [PaymentController::class, 'webhookDebug'])->name('webhooks.payhere.debug');
Route::get('/webhooks/payhere/test', function() {
    return response()->json(['status' => 'Webhook endpoint is accessible']);
});

// Wishlist routes
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [App\Http\Controllers\WishlistController::class, 'index'])->name('index');
    Route::post('/add', [App\Http\Controllers\WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [App\Http\Controllers\WishlistController::class, 'remove'])->name('remove');
    Route::post('/clear', [App\Http\Controllers\WishlistController::class, 'clear'])->name('clear');
    Route::get('/count', [App\Http\Controllers\WishlistController::class, 'getCount'])->name('count');
    Route::post('/check', [App\Http\Controllers\WishlistController::class, 'check'])->name('check');
});

// Guest order tracking
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
    $order->load(['items.product', 'statusHistory']);
    return view('orders.track-guest-result', compact('order'));
})->name('orders.track-guest-result');


// Legacy order route redirect (MOVED TO END to avoid conflicts)
Route::get('/orders', function () {
    return redirect()->route('user.orders.index');
})->name('orders.index');

// API routes
Route::prefix('api/v1')->group(function () {
    Route::get('/products/{product}/variants', [ProductController::class, 'getVariantDetails'])->name('api.products.variants');
    Route::get('/search/autocomplete', [SearchController::class, 'suggestions'])->name('api.search.autocomplete');
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('api.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');
        Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('api.orders.track');
        Route::post('/orders/{order}/complete', [OrderController::class, 'markComplete'])->name('api.orders.complete');
    });
});

// TEST ROUTE FOR MANUAL ORDER UPDATE (REMOVE IN PRODUCTION)
Route::get('/test-payment-update/{orderNumber}', function($orderNumber) {
    $order = \App\Models\Order::where('order_number', $orderNumber)->first();
    if ($order) {
        $order->payment_status = 'completed';
        $order->status = 'payment_completed';
        $order->payment_reference = 'TEST-' . now()->timestamp;
        $order->save();
        
        return 'Order updated successfully. <a href="' . route('checkout.success', $order) . '">Go to success page</a>';
    }
    return 'Order not found';
})->middleware('auth');

// Route model binding customizations
Route::bind('order', function ($value) {
    $order = \App\Models\Order::findOrFail($value);
    
    if (request()->is('admin/*')) {
        return $order;
    }
    
    if (Auth::check() && Auth::id() === $order->user_id) {
        return $order;
    }
    
    if (request()->is('track-order/*')) {
        return $order;
    }
    
    abort(403, 'Unauthorized access to this order.');
});

Route::bind('product', function ($value, $route) {
    $product = \App\Models\Product::where('slug', $value)
        ->orWhere('id', $value)
        ->firstOrFail();
    
    if ($route->getName() === 'products.show') {
        $product->increment('views_count');
    }
    
    return $product;
});











// User Account Management Routes
// User Account Management Routes
Route::middleware(['auth', 'verified'])->prefix('account')->name('user.account.')->group(function () {
    // Dashboard
    Route::get('/', [UserAccountController::class, 'index'])->name('index');
    
    // Profile
    Route::get('/profile', [UserAccountController::class, 'editProfile'])->name('profile');
    Route::put('/profile', [UserAccountController::class, 'updateProfile'])->name('profile.update');
    
    // Addresses
    Route::get('/addresses', [UserAccountController::class, 'addresses'])->name('addresses');
    Route::get('/addresses/create', [UserAccountController::class, 'createAddress'])->name('addresses.create');
    Route::post('/addresses', [UserAccountController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [UserAccountController::class, 'editAddress'])->name('addresses.edit');
    Route::put('/addresses/{address}', [UserAccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [UserAccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::post('/addresses/{address}/default', [UserAccountController::class, 'makeDefaultAddress'])->name('addresses.default'); // ADD THIS
    
    // Security
    Route::get('/security', [UserAccountController::class, 'security'])->name('security');
    Route::put('/security/password', [UserAccountController::class, 'updatePassword'])->name('security.password'); // ADD THIS
    Route::post('/security/two-factor', [UserAccountController::class, 'enableTwoFactor'])->name('security.two-factor'); // ADD THIS
    Route::post('/logout-other-sessions', [UserAccountController::class, 'logoutOtherSessions'])->name('logout-other-sessions');
    Route::delete('/delete-account', [UserAccountController::class, 'deleteAccount'])->name('delete'); // ADD THIS
});



// Reviews routes
Route::middleware(['auth', 'verified'])->prefix('reviews')->name('user.reviews.')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('index');
    Route::get('/create/{order}/{product}', [ReviewController::class, 'createSingle'])->name('create.single');
    Route::post('/create/{order}/{product}', [ReviewController::class, 'storeSingle'])->name('store.single');
    Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
});


// Simplified Complaints routes 
Route::middleware(['auth', 'verified'])->prefix('complaints')->name('user.complaints.')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('index');
    Route::get('/create', [ComplaintController::class, 'create'])->name('create');
    Route::post('/', [ComplaintController::class, 'store'])->name('store');
    Route::get('/{complaint}', [ComplaintController::class, 'show'])->name('show');
    Route::post('/{complaint}/respond', [ComplaintController::class, 'respond'])->name('respond');
    
});



