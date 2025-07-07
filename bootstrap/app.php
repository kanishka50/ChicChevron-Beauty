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
        ]);
        
        // Configure where to redirect guests
        $middleware->redirectGuestsTo(fn () => route('login'));
        
        // Configure where to redirect authenticated users
        $middleware->redirectUsersTo(function () {
            if (auth()->guard('admin')->check()) {
                return '/admin/dashboard'; // Use URL instead of route name
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();