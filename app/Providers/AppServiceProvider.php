<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    // Register CartService
    $this->app->singleton(CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //// Force HTTPS for all URLs when using ngrok
        if (app()->environment('local') && strpos(config('app.url'), 'ngrok') !== false) {
            URL::forceScheme('https');
        }
        
    }
}
