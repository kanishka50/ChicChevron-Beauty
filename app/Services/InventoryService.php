<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Add stock to inventory
     */
    public function addStock(
        $productId,
        $productVariantId = null,
        $quantity,
        $costPerUnit = 0,
        $notes = 'Stock received',
        $supplier = null,
        $orderId = null
    ) {
        DB::beginTransaction();

        try {
            // Get or create inventory record
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);

            // Create inventory movement
            $movement = InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_IN,
                'quantity' => $quantity,
                'cost_per_unit' => $costPerUnit,
                'order_id' => $orderId,
                'supplier' => $supplier,
                'notes' => $notes,
            ]);

            // Update current stock
            $inventory->increment('stock_quantity', $quantity);

            DB::commit();

            return [
                'success' => true,
                'movement' => $movement,
                'new_stock_level' => $inventory->fresh()->stock_quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove stock from inventory (for sales)
     */
    public function removeStock(
        $productId,
        $productVariantId = null,
        $quantity,
        $notes = 'Stock sold',
        $orderId = null
    ) {
        DB::beginTransaction();

        try {
            // Get inventory record
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);

            // Check if enough stock available
            $availableStock = $inventory->stock_quantity - $inventory->reserved_quantity;
            if ($availableStock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$availableStock}, Required: {$quantity}");
            }

            // Create movement record
            $movement = InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_SOLD,
                'quantity' => -$quantity, // Negative for outgoing
                'cost_per_unit' => 0,
                'order_id' => $orderId,
                'notes' => $notes,
            ]);

            // Update current stock
            $inventory->decrement('stock_quantity', $quantity);

            DB::commit();

            return [
                'success' => true,
                'movement' => $movement,
                'new_stock_level' => $inventory->fresh()->stock_quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reserve stock for orders (temporarily holds stock)
     */
    public function reserveStock($productId, $productVariantId = null, $quantity, $referenceType = 'order', $referenceId = null)
    {
        DB::beginTransaction();

        try {
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);

            // Check if enough stock available
            $availableStock = $inventory->stock_quantity - $inventory->reserved_quantity;
            if ($availableStock < $quantity) {
                throw new \Exception("Insufficient stock to reserve. Available: {$availableStock}, Required: {$quantity}");
            }

            // Increase reserved stock
            $inventory->increment('reserved_quantity', $quantity);

            // Log reservation
            InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_RESERVED,
                'quantity' => -$quantity,
                'cost_per_unit' => 0,
                'order_id' => $referenceType === 'order' ? $referenceId : null,
                'notes' => 'Stock reserved for order',
            ]);

            DB::commit();

            return [
                'success' => true,
                'reserved_quantity' => $quantity,
                'available_stock' => $inventory->fresh()->stock_quantity - $inventory->fresh()->reserved_quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Release reserved stock (cancel reservation)
     */
    public function releaseReservedStock($productId, $productVariantId = null, $quantity, $referenceType = 'order', $referenceId = null)
    {
        DB::beginTransaction();

        try {
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);

            // Decrease reserved stock
            $inventory->decrement('reserved_quantity', max(0, min($quantity, $inventory->reserved_quantity)));

            // Log release
            InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_RELEASED,
                'quantity' => $quantity,
                'cost_per_unit' => 0,
                'order_id' => $referenceType === 'order' ? $referenceId : null,
                'notes' => 'Reserved stock released',
            ]);

            DB::commit();

            return [
                'success' => true,
                'released_quantity' => $quantity,
                'available_stock' => $inventory->fresh()->stock_quantity - $inventory->fresh()->reserved_quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Convert reserved stock to actual sale
     */
    public function confirmReservedStock($productId, $productVariantId = null, $quantity, $referenceType = 'order', $referenceId = null)
    {
        DB::beginTransaction();

        try {
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);

            // Release the reservation first
            $inventory->decrement('reserved_quantity', max(0, min($quantity, $inventory->reserved_quantity)));

            // Deduct from actual stock
            $inventory->decrement('stock_quantity', $quantity);

            // Log the sale
            InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_SOLD,
                'quantity' => -$quantity,
                'cost_per_unit' => 0,
                'order_id' => $referenceType === 'order' ? $referenceId : null,
                'notes' => 'Stock sold - order completed',
            ]);

            DB::commit();

            return [
                'success' => true,
                'new_stock_level' => $inventory->fresh()->stock_quantity
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Adjust stock (manual correction)
     */
    public function adjustStock($productId, $productVariantId = null, $newQuantity, $notes = 'Manual adjustment')
    {
        DB::beginTransaction();

        try {
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);
            $currentStock = $inventory->stock_quantity;
            $difference = $newQuantity - $currentStock;

            if ($difference == 0) {
                return ['success' => true, 'message' => 'No adjustment needed'];
            }

            // Create adjustment movement
            $movement = InventoryMovement::create([
                'product_variant_id' => $productVariantId,
                'type' => InventoryMovement::TYPE_ADJUSTMENT,
                'quantity' => $difference,
                'cost_per_unit' => 0,
                'notes' => $notes,
            ]);

            // Update stock
            $inventory->update(['stock_quantity' => $newQuantity]);

            DB::commit();

            return [
                'success' => true,
                'adjustment' => $difference,
                'old_stock' => $currentStock,
                'new_stock' => $newQuantity,
                'movement' => $movement
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get current stock levels
     */
    public function getStockDetails($productId, $productVariantId = null)
    {
        $inventory = $this->getOrCreateInventory($productId, $productVariantId);

        // Get recent movements for this variant
        $recentMovements = InventoryMovement::where('product_variant_id', $productVariantId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate average cost from recent 'in' movements
        $avgCost = InventoryMovement::where('product_variant_id', $productVariantId)
            ->where('type', InventoryMovement::TYPE_IN)
            ->where('cost_per_unit', '>', 0)
            ->avg('cost_per_unit') ?? 0;

        return [
            'inventory' => $inventory,
            'available_stock' => $inventory->stock_quantity - $inventory->reserved_quantity,
            'batches' => $recentMovements,
            'oldest_batch_date' => $recentMovements->last()->created_at ?? null,
            'newest_batch_date' => $recentMovements->first()->created_at ?? null,
            'average_cost' => $avgCost
        ];
    }

    /**
     * Get low stock items
     */
    public function getLowStockItems()
    {
        return Inventory::with(['productVariant.product'])
            ->whereRaw('(stock_quantity - reserved_quantity) <= low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();
    }

    /**
     * Get out of stock items
     */
    public function getOutOfStockItems()
    {
        return Inventory::with(['productVariant.product'])
            ->whereRaw('(stock_quantity - reserved_quantity) <= 0')
            ->get();
    }

    /**
     * Get or create inventory record
     */
    private function getOrCreateInventory($productId, $productVariantId = null)
    {
        return Inventory::firstOrCreate(
            [
                'product_variant_id' => $productVariantId,
            ],
            [
                'stock_quantity' => 0,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 10,
            ]
        );
    }

    /**
     * Transfer stock between variants
     */
    public function transferStock(
        $fromProductId,
        $fromVariantId,
        $toProductId,
        $toVariantId,
        $quantity,
        $notes = 'Stock transfer'
    ) {
        DB::beginTransaction();

        try {
            // Get cost from source variant
            $stockDetails = $this->getStockDetails($fromProductId, $fromVariantId);
            $averageCost = $stockDetails['average_cost'];

            // Remove from source
            $this->removeStock($fromProductId, $fromVariantId, $quantity, $notes . ' (from)');

            // Add to destination
            $this->addStock($toProductId, $toVariantId, $quantity, $averageCost, $notes . ' (to)');

            DB::commit();

            return [
                'success' => true,
                'quantity_transferred' => $quantity,
                'from_variant' => ProductVariant::find($fromVariantId),
                'to_variant' => ProductVariant::find($toVariantId)
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get inventory report data
     */
    public function getInventoryReport($filters = [])
    {
        $query = Inventory::with(['productVariant.product']);

        if (isset($filters['status'])) {
            switch ($filters['status']) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'out':
                    $query->outOfStock();
                    break;
                case 'good':
                    $query->inStock()
                          ->whereRaw('(stock_quantity - reserved_quantity) > low_stock_threshold');
                    break;
            }
        }

        if (isset($filters['category_id'])) {
            $query->whereHas('productVariant.product', function ($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (isset($filters['brand_id'])) {
            $query->whereHas('productVariant.product', function ($q) use ($filters) {
                $q->where('brand_id', $filters['brand_id']);
            });
        }

        return $query->get();
    }
}
