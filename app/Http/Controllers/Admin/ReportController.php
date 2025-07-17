<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the reports dashboard
     */
    public function index()
    {
        // Get quick stats for the dashboard
        $stats = [
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_customers' => User::count(),
            'total_products' => Product::count(),
        ];

        // Get recent summary data
        $recentSales = $this->reportService->getRecentSalesData(7); // Last 7 days
        
        return view('admin.reports.index', compact('stats', 'recentSales'));
    }

    /**
     * Display sales report
     */
    public function sales(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get filter parameters
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'product_id' => $request->input('product_id'),
        ];

        // Get sales data
        $salesData = $this->reportService->getSalesReport($filters);
        
        // Get data for charts
        $chartData = [
            'daily_sales' => $this->reportService->getDailySalesChart($filters),
            'top_products' => $this->reportService->getTopProductsChart($filters),
            'sales_by_category' => $this->reportService->getSalesByCategoryChart($filters),
            'payment_methods' => $this->reportService->getPaymentMethodsChart($filters),
        ];

        // Get filters data
        $categories = \App\Models\Category::active()->get();
        $brands = \App\Models\Brand::active()->get();
        
        return view('admin.reports.sales', compact(
            'salesData', 
            'chartData', 
            'filters', 
            'categories', 
            'brands'
        ));
    }

    /**
     * Display inventory report
     */
    public function inventory(Request $request)
    {
        // Get filter parameters
        $filters = [
            'status' => $request->input('status', 'all'), // all, low, out, good
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'sort_by' => $request->input('sort_by', 'stock_level'), // stock_level, value, name
        ];

        // Get inventory data
        $inventoryData = $this->reportService->getInventoryReport($filters);
        
        // Get summary statistics
        $stats = [
            'total_products' => $inventoryData['total_products'],
            'total_variants' => $inventoryData['total_variants'],
            'low_stock_count' => $inventoryData['low_stock_count'],
            'out_of_stock_count' => $inventoryData['out_of_stock_count'],
            'total_inventory_value' => $inventoryData['total_value'],
        ];

        // Get chart data
        $chartData = [
            'stock_levels' => $this->reportService->getStockLevelsChart(),
            'inventory_value_by_category' => $this->reportService->getInventoryValueByCategoryChart(),
            'stock_movement' => $this->reportService->getStockMovementChart($request->input('days', 30)),
        ];

        // Get filters data
        $categories = \App\Models\Category::active()->get();
        $brands = \App\Models\Brand::active()->get();
        
        return view('admin.reports.inventory', compact(
            'inventoryData', 
            'stats', 
            'chartData', 
            'filters', 
            'categories', 
            'brands'
        ));
    }

    /**
     * Display customer report
     */
    public function customers(Request $request)
    {
        // Get date range
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'sort_by' => $request->input('sort_by', 'total_spent'), // total_spent, order_count, registration_date
        ];

        // Get customer data
        $customerData = $this->reportService->getCustomerReport($filters);
        
        // Get summary statistics
        $stats = [
            'total_customers' => $customerData['total_customers'],
            'new_customers' => $customerData['new_customers'],
            'returning_customers' => $customerData['returning_customers'],
            'average_order_value' => $customerData['average_order_value'],
            'total_revenue' => $customerData['total_revenue'],
        ];

        // Get chart data
        $chartData = [
            'registration_trend' => $this->reportService->getRegistrationTrendChart($filters),
            'customer_distribution' => $this->reportService->getCustomerDistributionChart(),
            'top_customers' => $this->reportService->getTopCustomersChart($filters),
            'order_frequency' => $this->reportService->getOrderFrequencyChart($filters),
        ];
        
        return view('admin.reports.customers', compact(
            'customerData', 
            'stats', 
            'chartData', 
            'filters'
        ));
    }

    /**
     * Export sales report
     */
    public function exportSales(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'format' => $request->input('format', 'excel'), // excel or pdf
        ];

        if ($filters['format'] === 'pdf') {
            $data = $this->reportService->getSalesReport($filters);
            $pdf = Pdf::loadView('admin.reports.exports.sales-pdf', compact('data', 'filters'));
            return $pdf->download('sales-report-' . date('Y-m-d') . '.pdf');
        }

        // Excel export
        return Excel::download(
            new \App\Exports\SalesReportExport($filters), 
            'sales-report-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export inventory report
     */
    public function exportInventory(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'format' => $request->input('format', 'excel'),
        ];

        if ($filters['format'] === 'pdf') {
            $data = $this->reportService->getInventoryReport($filters);
            $pdf = Pdf::loadView('admin.reports.exports.inventory-pdf', compact('data', 'filters'));
            return $pdf->download('inventory-report-' . date('Y-m-d') . '.pdf');
        }

        // return Excel::download(
        //     new \App\Exports\InventoryReportExport($filters), 
        //     'inventory-report-' . date('Y-m-d') . '.xlsx'
        // );
    }

    /**
     * Export customer report
     */
    public function exportCustomers(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'format' => $request->input('format', 'excel'),
        ];

        if ($filters['format'] === 'pdf') {
            $data = $this->reportService->getCustomerReport($filters);
            $pdf = Pdf::loadView('admin.reports.exports.customers-pdf', compact('data', 'filters'));
            return $pdf->download('customer-report-' . date('Y-m-d') . '.pdf');
        }

        // return Excel::download(
        //     new \App\Exports\CustomerReportExport($filters), 
        //     'customer-report-' . date('Y-m-d') . '.xlsx'
        // );
    }

    /**
     * Get sales data via AJAX (for dynamic chart updates)
     */
    public function getSalesData(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'chart_type' => $request->input('chart_type', 'daily_sales'),
        ];

        $data = match($filters['chart_type']) {
            'daily_sales' => $this->reportService->getDailySalesChart($filters),
            'top_products' => $this->reportService->getTopProductsChart($filters),
            'sales_by_category' => $this->reportService->getSalesByCategoryChart($filters),
            'payment_methods' => $this->reportService->getPaymentMethodsChart($filters),
            default => []
        };

        return response()->json($data);
    }
}