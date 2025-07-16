<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory dashboard
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['product.brand', 'productVariant']);

        // Filter by stock status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low':
                    $query->whereRaw('(current_stock - reserved_stock) <= low_stock_threshold')
                          ->where('current_stock', '>', 0);
                    break;
                case 'out':
                    $query->whereRaw('(current_stock - reserved_stock) <= 0');
                    break;
                case 'good':
                    $query->whereRaw('(current_stock - reserved_stock) > low_stock_threshold');
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhereHas('productVariant', function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $inventories = $query->orderBy('current_stock', 'asc')->paginate(20);

        // Get summary statistics
        $stats = [
            'total_products' => Inventory::count(),
            'low_stock' => Inventory::whereRaw('(current_stock - reserved_stock) <= low_stock_threshold')
                                  ->where('current_stock', '>', 0)->count(),
            'out_of_stock' => Inventory::whereRaw('(current_stock - reserved_stock) <= 0')->count(),
            'total_value' => $this->calculateTotalInventoryValue(),
        ];

        return view('admin.inventory.index', compact('inventories', 'stats'));
    }

    /**
     * Show stock movements history
     */
    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['inventory.product', 'inventory.productVariant'])
                                 ->orderBy('movement_date', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('movement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('movement_date', '<=', $request->date_to . ' 23:59:59');
        }

        // Filter by movement type
        if ($request->filled('type')) {
            $query->where('movement_type', $request->type);
        }

        // Search by product
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('inventory.product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhereHas('inventory.productVariant', function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $movements = $query->paginate(50);

        return view('admin.inventory.movements', compact('movements'));
    }

    /**
     * Add stock to inventory
     */
    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'cost_per_unit' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $result = $this->inventoryService->addStock(
                $request->product_id,
                $request->product_variant_id,
                $request->quantity,
                $request->cost_per_unit,
                $request->reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully!',
                'batch_number' => $result['batch_number'],
                'new_stock_level' => $result['new_stock_level']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjust stock manually
     */
    public function adjustStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $result = $this->inventoryService->adjustStock(
                $request->product_id,
                $request->product_variant_id,
                $request->new_quantity,
                $request->reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully!',
                'adjustment' => $result['adjustment'],
                'old_stock' => $result['old_stock'],
                'new_stock' => $result['new_stock']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adjusting stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get variant inventory data
     */
    public function getVariant(ProductVariant $variant)
    {
        try {
            $inventory = $variant->inventory ?? new Inventory([
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10
            ]);

            // Get FIFO batch details
            $stockDetails = $this->inventoryService->getStockDetails(
                $variant->product_id,
                $variant->id
            );

            return response()->json([
                'success' => true,
                'inventory' => $inventory,
                'stock_details' => $stockDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading inventory data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update variant inventory
     */
    public function updateVariant(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'current_stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0'
        ]);

        try {
            // Get or create inventory
            $inventory = $variant->inventory ?? Inventory::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10
            ]);

            $oldStock = $inventory->current_stock;
            $newStock = $request->current_stock;

            // If stock changed, use adjustment method
            if ($oldStock != $newStock) {
                $this->inventoryService->adjustStock(
                    $variant->product_id,
                    $variant->id,
                    $newStock,
                    'Manual stock update from variant management'
                );
            }

            // Update threshold
            $inventory->update(['low_stock_threshold' => $request->low_stock_threshold]);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully!',
                'inventory' => $inventory->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock details for a specific product/variant
     */
    public function getStockDetails(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        try {
            $details = $this->inventoryService->getStockDetails(
                $request->product_id,
                $request->product_variant_id
            );

            return response()->json([
                'success' => true,
                'stock_details' => $details
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading stock details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts()
    {
        try {
            $lowStockItems = $this->inventoryService->getLowStockItems();
            $outOfStockItems = $this->inventoryService->getOutOfStockItems();

            return response()->json([
                'success' => true,
                'low_stock' => $lowStockItems,
                'out_of_stock' => $outOfStockItems,
                'total_alerts' => $lowStockItems->count() + $outOfStockItems->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading alerts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export inventory report
     */
    public function exportReport(Request $request)
    {
        try {
            $inventories = Inventory::with(['product', 'productVariant'])
                ->get()
                ->map(function ($inventory) {
                    return [
                        'Product' => $inventory->product->name,
                        'Variant' => $inventory->productVariant->name,
                        'SKU' => $inventory->productVariant->sku,
                        'Current Stock' => $inventory->current_stock,
                        'Reserved Stock' => $inventory->reserved_stock,
                        'Available Stock' => $inventory->available_stock,
                        'Low Stock Threshold' => $inventory->low_stock_threshold,
                        'Status' => $inventory->stock_status,
                        'Value' => $inventory->current_stock * $inventory->productVariant->cost_price,
                    ];
                });

            $filename = 'inventory_report_' . date('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($inventories) {
                $file = fopen('php://output', 'w');
                fputcsv($file, array_keys($inventories->first()));
                
                foreach ($inventories as $row) {
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }

    /**
     * Calculate total inventory value using FIFO
     */
    private function calculateTotalInventoryValue()
    {
        return DB::table('inventory_movements')
            ->where('movement_type', 'in')
            ->where('quantity', '>', 0)
            ->sum(DB::raw('quantity * cost_per_unit'));
    }
}