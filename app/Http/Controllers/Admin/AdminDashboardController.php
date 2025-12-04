<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Cache dashboard stats for 5 minutes to improve performance
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return $this->calculateStats();
        });

        // Get recent activities (optional)
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard.index', compact('stats', 'recentActivities'));
    }

    /**
     * Calculate dashboard statistics.
     */
    private function calculateStats()
    {
        $today = now()->startOfDay();

        return [
            'today_sales' => Order::whereDate('created_at', $today)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),

            'today_orders' => Order::whereDate('created_at', $today)->count(),

            'low_stock' => Inventory::whereRaw('current_stock <= low_stock_threshold')
                ->where('current_stock', '>', 0)
                ->count(),

            'out_of_stock' => Inventory::where('current_stock', 0)->count(),

            'new_customers' => User::whereDate('created_at', $today)->count(),

            'pending_orders' => Order::where('status', 'processing')->count(),
        ];
    }

    /**
     * Get recent activities for the dashboard.
     */
    private function getRecentActivities()
    {
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'message' => "New order #{$order->order_number} from {$order->user->name}",
                    'time' => $order->created_at,
                    'link' => route('admin.orders.show', $order->id)
                ];
            });

        // Get recent registrations
        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'message' => "New customer registered: {$user->name}",
                    'time' => $user->created_at,
                    'link' => null
                ];
            });

        // Combine and sort by time
        return collect()
            ->merge($recentOrders)
            ->merge($recentUsers)
            ->sortByDesc('time')
            ->take(10);
    }

    /**
     * Refresh dashboard statistics (AJAX endpoint).
     */
    public function refreshStats()
    {
        Cache::forget('dashboard_stats');
        $stats = $this->calculateStats();

        return response()->json($stats);
    }
}