<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'guest.or.auth' => \App\Http\Middleware\GuestOrAuth::class,

            
        ]);
        
        // Configure guest middleware to use correct guard
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->expectsJson()) {
                return null;
            }
            
            // Always redirect to the single login page
            return route('login');
        });
        
        // Configure authenticated users redirect
        $middleware->redirectUsersTo(function ($request) {
            if (auth()->guard('admin')->check()) {
                return route('admin.dashboard');
            }
            return route('home');
        });


        // Configure authentication guards
        $middleware->appendToGroup('auth:web,admin', [
        \Illuminate\Auth\Middleware\Authenticate::class.':web,admin',
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();