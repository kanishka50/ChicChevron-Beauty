<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestOrAuth
{
    public function handle(Request $request, Closure $next)
    {
        // This middleware allows both guests and authenticated users
        // Useful for cart and checkout functionality
        return $next($request);
    }
}