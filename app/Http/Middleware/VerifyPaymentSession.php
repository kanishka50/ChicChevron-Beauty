<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class VerifyPaymentSession
{
    public function handle(Request $request, Closure $next)
    {
        $order = $request->route('order');
        
        if (!$order instanceof Order) {
            abort(404);
        }

        // Check if user is authenticated first
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue.');
        }

        // Now we can safely use Auth::id()
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // For payment success route, verify webhook was received
        if ($request->routeIs('checkout.payment.success')) {
            if (!$order->isWebhookReceived() && $order->payment_status !== 'completed') {
                // Check if payment session expired
                if ($order->payment_initiated_at && 
                    $order->payment_initiated_at->addMinutes(30)->isPast()) {
                    return redirect()->route('checkout.index')
                        ->with('error', 'Payment session expired. Please try again.');
                }
            }
        }

        return $next($request);
    }
}