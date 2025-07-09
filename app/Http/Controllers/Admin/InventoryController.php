<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\VariantCombination;
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
        $query = Inventory::with(['product.brand', 'variantCombination.sizeVariant', 'variantCombination.colorVariant', 'variantCombination.scentVariant']);

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
        $query = InventoryMovement::with(['inventory.product', 'inventory.variantCombination'])
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
            'variant_combination_id' => 'nullable|exists:variant_combinations,id',
            'quantity' => 'required|integer|min:1',
            'cost_per_unit' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $result = $this->inventoryService->addStock(
                $request->product_id,
                $request->variant_combination_id,
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
            'variant_combination_id' => 'nullable|exists:variant_combinations,id',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $result = $this->inventoryService->adjustStock(
                $request->product_id,
                $request->variant_combination_id,
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
     * Get combination inventory data (for variant management)
     */
    public function getCombination(VariantCombination $combination)
    {
        try {
            $inventory = $combination->inventory ?? new Inventory([
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10
            ]);

            // Get FIFO batch details
            $stockDetails = $this->inventoryService->getStockDetails(
                $combination->product_id,
                $combination->id
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
     * Update combination inventory
     */
    public function updateCombination(Request $request, VariantCombination $combination)
    {
        $request->validate([
            'current_stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0'
        ]);

        try {
            // Get current inventory
            $inventory = $combination->inventory ?? new Inventory([
                'product_id' => $combination->product_id,
                'variant_combination_id' => $combination->id,
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10
            ]);

            $oldStock = $inventory->current_stock ?? 0;
            $newStock = $request->current_stock;

            // If stock changed, use adjustment method
            if ($oldStock != $newStock) {
                $this->inventoryService->adjustStock(
                    $combination->product_id,
                    $combination->id,
                    $newStock,
                    'Manual stock update from variant management'
                );
            }

            // Update threshold
            if ($inventory->exists) {
                $inventory->update(['low_stock_threshold' => $request->low_stock_threshold]);
            } else {
                $inventory->low_stock_threshold = $request->low_stock_threshold;
                $inventory->save();
            }

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
            'variant_combination_id' => 'nullable|exists:variant_combinations,id',
        ]);

        try {
            $details = $this->inventoryService->getStockDetails(
                $request->product_id,
                $request->variant_combination_id
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
        // This would generate CSV/PDF export
        // Implementation depends on your preferred export method
        
        return response()->json([
            'success' => true,
            'message' => 'Export functionality to be implemented'
        ]);
    }

    /**
     * Calculate total inventory value using FIFO
     */
    private function calculateTotalInventoryValue()
    {
        return DB::table('inventory_movements')
            ->select(DB::raw('SUM(
                CASE 
                    WHEN movement_type = "in" THEN quantity * cost_per_unit
                    ELSE 0
                END
            ) as total_value'))
            ->whereNotNull('cost_per_unit')
            ->value('total_value') ?? 0;
    }
}