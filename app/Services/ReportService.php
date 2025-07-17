<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get recent sales data for dashboard
     */
    public function getRecentSalesData($days = 7)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return Order::where('payment_status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get comprehensive sales report
     */
    public function getSalesReport($filters)
    {
        $query = Order::where('payment_status', 'completed')
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);

        // Apply filters
        if (!empty($filters['category_id'])) {
            $query->whereHas('items.product', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (!empty($filters['brand_id'])) {
            $query->whereHas('items.product', function ($q) use ($filters) {
                $q->where('brand_id', $filters['brand_id']);
            });
        }

        // Get summary data
        $summary = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total_amount'),
            'total_discount' => $query->sum('discount_amount'),
            'total_shipping' => $query->sum('shipping_amount'),
            'average_order_value' => $query->avg('total_amount'),
        ];

        // Get detailed sales by product
        $productSales = OrderItem::whereHas('order', function ($q) use ($filters) {
                $q->where('payment_status', 'completed')
                  ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);
            })
            ->with(['product', 'productVariant'])
            ->groupBy('product_id', 'product_variant_id')
            ->select(
                'product_id',
                'product_variant_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('SUM(quantity * cost_price) as total_cost'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->map(function ($item) {
                $item->profit = $item->total_revenue - $item->total_cost;
                $item->profit_margin = $item->total_revenue > 0 
                    ? ($item->profit / $item->total_revenue) * 100 
                    : 0;
                return $item;
            });

        // Get sales by date
        $dailySales = Order::where('payment_status', 'completed')
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(discount_amount) as discounts')
            )
            ->orderBy('date')
            ->get();

        return [
            'summary' => $summary,
            'product_sales' => $productSales,
            'daily_sales' => $dailySales,
        ];
    }

    /**
     * Get daily sales chart data
     */
    public function getDailySalesChart($filters)
    {
        $data = Order::where('payment_status', 'completed')
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Revenue (LKR)',
                    'data' => $data->pluck('revenue')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.1
                ],
                [
                    'label' => 'Orders',
                    'data' => $data->pluck('orders')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'yAxisID' => 'y1',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    /**
     * Get top products chart data
     */
    public function getTopProductsChart($filters, $limit = 10)
    {
        $data = OrderItem::whereHas('order', function ($q) use ($filters) {
                $q->where('payment_status', 'completed')
                  ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);
            })
            ->with(['product', 'productVariant'])
            ->groupBy('product_id', 'product_variant_id')
            ->select(
                'product_id',
                'product_variant_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();

        $labels = [];
        $revenues = [];
        $quantities = [];

        foreach ($data as $item) {
            $productName = $item->product->name;
            if ($item->productVariant) {
                $productName .= ' - ' . $item->productVariant->name;
            }
            $labels[] = $productName;
            $revenues[] = $item->total_revenue;
            $quantities[] = $item->total_quantity;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Revenue (LKR)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ],
                [
                    'label' => 'Quantity Sold',
                    'data' => $quantities,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'yAxisID' => 'y1',
                ]
            ]
        ];
    }

    /**
     * Get sales by category chart data
     */
    public function getSalesByCategoryChart($filters)
    {
        $data = OrderItem::whereHas('order', function ($q) use ($filters) {
                $q->where('payment_status', 'completed')
                  ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59']);
            })
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.total_price) as revenue')
            )
            ->orderBy('revenue', 'desc')
            ->get();

        return [
            'labels' => $data->pluck('category_name')->toArray(),
            'datasets' => [
                [
                    'data' => $data->pluck('revenue')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get payment methods chart data
     */
    public function getPaymentMethodsChart($filters)
    {
        $data = Order::where('payment_status', 'completed')
            ->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
            ->groupBy('payment_method')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->get();

        return [
            'labels' => $data->pluck('payment_method')->map(fn($method) => strtoupper($method))->toArray(),
            'datasets' => [
                [
                    'data' => $data->pluck('revenue')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get inventory report
     */
public function getInventoryReport($filters)
{
    $query = Inventory::with(['product.brand', 'product.category', 'productVariant']);

    // Apply status filter
    switch ($filters['status']) {
        case 'low':
            $query->whereRaw('current_stock <= low_stock_threshold')
                  ->where('current_stock', '>', 0);
            break;
        case 'out':
            $query->where('current_stock', 0);
            break;
        case 'good':
            $query->whereRaw('current_stock > low_stock_threshold');
            break;
    }

    // Apply category filter
    if (!empty($filters['category_id'])) {
        $query->whereHas('product', function ($q) use ($filters) {
            $q->where('category_id', $filters['category_id']);
        });
    }

    // Apply brand filter
    if (!empty($filters['brand_id'])) {
        $query->whereHas('product', function ($q) use ($filters) {
            $q->where('brand_id', $filters['brand_id']);
        });
    }

    // Get items
    $items = $query->get()->map(function ($inventory) {
        // Since all inventory should have a productVariant in your system
        // we calculate based on variant cost_price
        if ($inventory->productVariant) {
            $inventory->stock_value = $inventory->current_stock * $inventory->productVariant->cost_price;
        } else {
            // Fallback to 0 if no variant exists
            $inventory->stock_value = 0;
        }
        
        $inventory->available_stock = $inventory->current_stock - $inventory->reserved_stock;
        return $inventory;
    });

    // Sort results
    switch ($filters['sort_by']) {
        case 'value':
            $items = $items->sortByDesc('stock_value');
            break;
        case 'name':
            $items = $items->sortBy(function ($item) {
                $productName = $item->product ? $item->product->name : 'Unknown Product';
                $variantName = $item->productVariant ? $item->productVariant->name : '';
                return $productName . ' ' . $variantName;
            });
            break;
        default:
            $items = $items->sortBy('current_stock');
    }

    // Calculate summary statistics with null checks
    $stats = [
        'total_products' => Product::count(),
        'total_variants' => ProductVariant::count(),
        'low_stock_count' => Inventory::whereRaw('current_stock <= low_stock_threshold')
                                    ->where('current_stock', '>', 0)->count(),
        'out_of_stock_count' => Inventory::where('current_stock', 0)->count(),
        'total_value' => $items->sum('stock_value'),
    ];

    return array_merge($stats, ['items' => $items]);
}

    /**
     * Get stock levels chart
     */
    public function getStockLevelsChart()
    {
        $data = [
            'Good Stock' => Inventory::whereRaw('current_stock > low_stock_threshold')->count(),
            'Low Stock' => Inventory::whereRaw('current_stock <= low_stock_threshold')
                                   ->where('current_stock', '>', 0)->count(),
            'Out of Stock' => Inventory::where('current_stock', 0)->count(),
        ];

        return [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get inventory value by category chart
     */
public function getInventoryValueByCategoryChart()
{
    $data = DB::table('inventory')
        ->join('products', 'inventory.product_id', '=', 'products.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('product_variants', 'inventory.product_variant_id', '=', 'product_variants.id')
        ->groupBy('categories.id', 'categories.name')
        ->select(
            'categories.name as category_name',
            DB::raw('SUM(
                inventory.current_stock * 
                COALESCE(product_variants.cost_price, 0)
            ) as total_value')
        )
        ->having('total_value', '>', 0)
        ->orderBy('total_value', 'desc')
        ->get();

    return [
        'labels' => $data->pluck('category_name')->toArray(),
        'datasets' => [
            [
                'data' => $data->pluck('total_value')->toArray(),
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                ],
            ]
        ]
    ];
}

    /**
     * Get stock movement chart
     */
public function getStockMovementChart($days = 30)
{
    $startDate = Carbon::now()->subDays($days);
    
    // Using inventory_movements as confirmed from your database structure
    $movements = DB::table('inventory_movements')
        ->where('movement_date', '>=', $startDate)
        ->groupBy(DB::raw('DATE(movement_date)'), 'movement_type')
        ->select(
            DB::raw('DATE(movement_date) as date'),
            'movement_type',
            DB::raw('SUM(quantity) as total_quantity')
        )
        ->orderBy('date')
        ->get();

    $dates = [];
    $inData = [];
    $outData = [];

    // Get all dates in range
    for ($i = 0; $i < $days; $i++) {
        $date = Carbon::now()->subDays($days - $i - 1)->format('Y-m-d');
        $dates[] = Carbon::parse($date)->format('M d');
        $inData[$date] = 0;
        $outData[$date] = 0;
    }

    // Fill in movement data
    foreach ($movements as $movement) {
        if ($movement->movement_type === 'in') {
            $inData[$movement->date] = $movement->total_quantity;
        } else {
            $outData[$movement->date] = abs($movement->total_quantity);
        }
    }

    return [
        'labels' => $dates,
        'datasets' => [
            [
                'label' => 'Stock In',
                'data' => array_values($inData),
                'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
            ],
            [
                'label' => 'Stock Out',
                'data' => array_values($outData),
                'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
            ]
        ]
    ];
}

    /**
     * Get customer report
     */
    public function getCustomerReport($filters)
    {
        // Get all customers with their order statistics
        $customers = User::withCount(['orders' => function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                      ->where('payment_status', 'completed');
            }])
            ->withSum(['orders' => function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                      ->where('payment_status', 'completed');
            }], 'total_amount')
            ->having('orders_count', '>', 0)
            ->get()
            ->map(function ($customer) {
                $customer->average_order_value = $customer->orders_count > 0 
                    ? $customer->orders_sum_total_amount / $customer->orders_count 
                    : 0;
                return $customer;
            });

        // Sort based on filter
        switch ($filters['sort_by']) {
            case 'order_count':
                $customers = $customers->sortByDesc('orders_count');
                break;
            case 'registration_date':
                $customers = $customers->sortByDesc('created_at');
                break;
            default:
                $customers = $customers->sortByDesc('orders_sum_total_amount');
        }

        // Calculate statistics
        $totalCustomers = User::count();
        $newCustomers = User::whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])->count();
        $returningCustomers = User::has('orders', '>', 1)->count();
        
        $totalRevenue = Order::whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                            ->where('payment_status', 'completed')
                            ->sum('total_amount');
        
        $totalOrders = Order::whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                           ->where('payment_status', 'completed')
                           ->count();

        return [
            'customers' => $customers->take(100), // Limit to top 100
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'total_revenue' => $totalRevenue,
        ];
    }

    /**
     * Get registration trend chart
     */
    public function getRegistrationTrendChart($filters)
    {
        $data = User::whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'New Registrations',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.5)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    /**
     * Get customer distribution chart (by location)
     */
    public function getCustomerDistributionChart()
    {
        $data = User::join('user_addresses', 'users.id', '=', 'user_addresses.user_id')
            ->where('user_addresses.is_default', true)
            ->groupBy('user_addresses.city')
            ->select(
                'user_addresses.city',
                DB::raw('COUNT(DISTINCT users.id) as count')
            )
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $data->pluck('city')->toArray(),
            'datasets' => [
                [
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(199, 199, 199, 0.8)',
                        'rgba(83, 102, 255, 0.8)',
                        'rgba(255, 99, 255, 0.8)',
                        'rgba(99, 255, 132, 0.8)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get top customers chart
     */
    public function getTopCustomersChart($filters, $limit = 10)
    {
        $data = User::withSum(['orders' => function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                      ->where('payment_status', 'completed');
            }], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->limit($limit)
            ->get();

        return [
            'labels' => $data->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Spent (LKR)',
                    'data' => $data->pluck('orders_sum_total_amount')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ]
            ]
        ];
    }

    /**
     * Get order frequency chart
     */
    public function getOrderFrequencyChart($filters)
    {
        $data = User::withCount(['orders' => function ($query) use ($filters) {
                $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date'] . ' 23:59:59'])
                      ->where('payment_status', 'completed');
            }])
            ->having('orders_count', '>', 0)
            ->get()
            ->groupBy('orders_count')
            ->map(function ($group, $orderCount) {
                return [
                    'order_count' => $orderCount,
                    'customer_count' => $group->count()
                ];
            })
            ->sortBy('order_count')
            ->take(10);

        return [
            'labels' => $data->pluck('order_count')->map(fn($count) => "{$count} orders")->toArray(),
            'datasets' => [
                [
                    'label' => 'Number of Customers',
                    'data' => $data->pluck('customer_count')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                ]
            ]
        ];
    }
}