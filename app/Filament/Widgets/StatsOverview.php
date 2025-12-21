<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrders = Order::where('status', 'processing')->count();
        $lowStockCount = Inventory::whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')
            ->whereRaw('stock_quantity - reserved_quantity > 0')
            ->count();
        $outOfStockCount = Inventory::whereRaw('stock_quantity - reserved_quantity <= 0')->count();

        return [
            Stat::make('Total Orders', Order::count())
                ->description('All time orders')
                ->color('primary'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting processing')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Low Stock Items', $lowStockCount + $outOfStockCount)
                ->description($outOfStockCount . ' out of stock')
                ->color($lowStockCount + $outOfStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Total Revenue', 'Rs. ' . number_format($totalRevenue, 2))
                ->description('From completed orders')
                ->color('success'),
        ];
    }
}
