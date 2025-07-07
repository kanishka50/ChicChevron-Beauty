<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public shop routes (placeholder for now)
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

// Guest only routes
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    
    // Login
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    
    // Password Reset
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    
});


    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('verify-email', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    // Logout
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Wishlist (authenticated users only)
    Route::get('/wishlist', function () {
        return view('welcome');
    })->name('wishlist.index');
    
    // Verified user routes
    Route::middleware('verified')->group(function () {
        // User account routes
        Route::get('/account', function () {
            return 'My Account';
        })->name('account.index');
        
        Route::get('/orders', function () {
            return 'My Orders';
        })->name('orders.index');
        
        Route::get('/complaints/create', function () {
            return 'Create Complaint';
        })->name('complaints.create');
    });


    
});

// Guest or Auth routes (accessible by both)
Route::middleware('guest.or.auth')->group(function () {
    Route::get('/cart', function () {
        return 'Shopping Cart';
    })->name('cart.index');
});


























// // Temporary routes for testing layouts
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// // Placeholder routes referenced in navigation
// Route::get('/products', function () {
//     return view('welcome');
// })->name('products.index');

// Route::get('/categories', function () {
//     return view('welcome');
// })->name('categories.index');

// Route::get('/categories/{category}', function () {
//     return view('welcome');
// })->name('categories.show');

// Route::get('/about', function () {
//     return view('welcome');
// })->name('about');

// Route::get('/contact', function () {
//     return view('welcome');
// })->name('contact');

// Route::get('/faq', function () {
//     return view('welcome');
// })->name('faq');

// Route::get('/terms', function () {
//     return view('welcome');
// })->name('terms');

// Route::get('/privacy', function () {
//     return view('welcome');
// })->name('privacy');

// Route::get('/search', function () {
//     return view('welcome');
// })->name('search');

// Route::get('/cart', function () {
//     return view('welcome');
// })->name('cart.index');

// Route::get('/wishlist', function () {
//     return view('welcome');
// })->name('wishlist.index');

// Route::get('/account', function () {
//     return view('welcome');
// })->name('account.index');

// Route::get('/orders', function () {
//     return view('welcome');
// })->name('orders.index');

// Route::get('/complaints/create', function () {
//     return view('welcome');
// })->name('complaints.create');

// // Auth routes placeholders
// Route::get('/login', function () {
//     return view('welcome');
// })->name('login');

// Route::get('/register', function () {
//     return view('welcome');
// })->name('register');

// Route::post('/logout', function () {
//     return redirect('/');
// })->name('logout');