<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load admin routes here
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'guest.or.auth' => \App\Http\Middleware\GuestOrAuth::class,
            'verify.payment.session' => \App\Http\Middleware\VerifyPaymentSession::class,
        ]);
        
        // Configure where to redirect guests
        $middleware->redirectGuestsTo(fn () => route('login'));

        // CSRF Token Exceptions for webhooks
        $middleware->validateCsrfTokens(except: [
            'webhooks/payhere',
            'webhooks/payhere/*',
            'webhooks/*'
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();