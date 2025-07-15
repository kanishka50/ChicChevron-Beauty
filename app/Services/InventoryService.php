<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryService
{
    /**
     * Add stock using FIFO - creates new batch
     */
    public function addStock(
        $productId, 
        $productVariantId = null, 
        $quantity, 
        $costPerUnit, 
        $reason = 'Stock received',
        $referenceType = null,
        $referenceId = null
    ) {
        DB::beginTransaction();
        
        try {
            // Get or create inventory record
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);
            
            // Generate batch number
            $batchNumber = $this->generateBatchNumber($productId, $productVariantId);
            
            // Create inventory movement
            $movement = InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'batch_number' => $batchNumber,
                'movement_type' => 'in',
                'quantity' => $quantity,
                'cost_per_unit' => $costPerUnit,
                'reason' => $reason,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'movement_date' => now(),
            ]);
            
            // Update current stock
            $inventory->increment('current_stock', $quantity);
            
            DB::commit();
            
            return [
                'success' => true,
                'batch_number' => $batchNumber,
                'movement' => $movement,
                'new_stock_level' => $inventory->fresh()->current_stock
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Remove stock using FIFO - uses oldest batches first
     */
    public function removeStock(
        $productId, 
        $productVariantId = null, 
        $quantity, 
        $reason = 'Stock sold',
        $referenceType = null,
        $referenceId = null
    ) {
        DB::beginTransaction();
        
        try {
            // Get inventory record
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);
            
            // Check if enough stock available
            $availableStock = $inventory->current_stock - $inventory->reserved_stock;
            if ($availableStock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$availableStock}, Required: {$quantity}");
            }
            
            // Get available batches in FIFO order (oldest first)
            $availableBatches = $this->getAvailableBatches($inventory->id);
            
            $remainingQuantity = $quantity;
            $movements = [];
            $totalCost = 0;
            
            foreach ($availableBatches as $batch) {
                if ($remainingQuantity <= 0) break;
                
                // Calculate how much to take from this batch
                $takeFromBatch = min($remainingQuantity, $batch->available_quantity);
                
                // Create movement record
                $movement = InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'batch_number' => $batch->batch_number,
                    'movement_type' => 'out',
                    'quantity' => -$takeFromBatch, // Negative for outgoing
                    'cost_per_unit' => $batch->cost_per_unit,
                    'reason' => $reason,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'movement_date' => now(),
                ]);
                
                $movements[] = $movement;
                $totalCost += $takeFromBatch * $batch->cost_per_unit;
                $remainingQuantity -= $takeFromBatch;
            }
            
            // Update current stock
            $inventory->decrement('current_stock', $quantity);
            
            DB::commit();
            
            return [
                'success' => true,
                'movements' => $movements,
                'total_cost' => $totalCost,
                'average_cost' => $quantity > 0 ? $totalCost / $quantity : 0,
                'new_stock_level' => $inventory->fresh()->current_stock
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
            $availableStock = $inventory->current_stock - $inventory->reserved_stock;
            if ($availableStock < $quantity) {
                throw new \Exception("Insufficient stock to reserve. Available: {$availableStock}, Required: {$quantity}");
            }
            
            // Increase reserved stock
            $inventory->increment('reserved_stock', $quantity);
            
            // Log reservation
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'batch_number' => 'RESERVED-' . now()->format('YmdHis'),
                'movement_type' => 'out',
                'quantity' => -$quantity,
                'cost_per_unit' => 0, // Cost will be calculated when actually removed
                'reason' => 'Stock reserved',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'movement_date' => now(),
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'reserved_quantity' => $quantity,
                'available_stock' => $inventory->fresh()->current_stock - $inventory->fresh()->reserved_stock
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
            $inventory->decrement('reserved_stock', max(0, min($quantity, $inventory->reserved_stock)));
            
            // Log release
            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'batch_number' => 'RELEASED-' . now()->format('YmdHis'),
                'movement_type' => 'in',
                'quantity' => $quantity,
                'cost_per_unit' => 0,
                'reason' => 'Reserved stock released',
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'movement_date' => now(),
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'released_quantity' => $quantity,
                'available_stock' => $inventory->fresh()->current_stock - $inventory->fresh()->reserved_stock
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Convert reserved stock to actual sale (FIFO removal)
     */
    public function confirmReservedStock($productId, $productVariantId = null, $quantity, $referenceType = 'order', $referenceId = null)
    {
        DB::beginTransaction();
        
        try {
            // First release the reservation
            $this->releaseReservedStock($productId, $productVariantId, $quantity, $referenceType, $referenceId);
            
            // Then remove stock using FIFO
            $result = $this->removeStock($productId, $productVariantId, $quantity, 'Stock sold', $referenceType, $referenceId);
            
            DB::commit();
            
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Adjust stock (manual correction)
     */
    public function adjustStock($productId, $productVariantId = null, $newQuantity, $reason = 'Manual adjustment')
    {
        DB::beginTransaction();
        
        try {
            $inventory = $this->getOrCreateInventory($productId, $productVariantId);
            $currentStock = $inventory->current_stock;
            $difference = $newQuantity - $currentStock;
            
            if ($difference == 0) {
                return ['success' => true, 'message' => 'No adjustment needed'];
            }
            
            // Create adjustment movement
            $movement = InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'batch_number' => 'ADJ-' . now()->format('YmdHis'),
                'movement_type' => 'adjustment',
                'quantity' => $difference,
                'cost_per_unit' => 0,
                'reason' => $reason,
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'movement_date' => now(),
            ]);
            
            // Update stock
            $inventory->update(['current_stock' => $newQuantity]);
            
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
     * Get current stock levels with FIFO batch details
     */
    public function getStockDetails($productId, $productVariantId = null)
    {
        $inventory = $this->getOrCreateInventory($productId, $productVariantId);
        $batches = $this->getAvailableBatches($inventory->id);
        
        return [
            'inventory' => $inventory,
            'available_stock' => $inventory->current_stock - $inventory->reserved_stock,
            'batches' => $batches,
            'oldest_batch_date' => $batches->first()->oldest_date ?? null,
            'newest_batch_date' => $batches->last()->newest_date ?? null,
            'average_cost' => $batches->avg('cost_per_unit') ?? 0
        ];
    }
    
    /**
     * Get low stock items
     */
    public function getLowStockItems()
    {
        return Inventory::with(['product', 'productVariant'])
            ->whereRaw('(current_stock - reserved_stock) <= low_stock_threshold')
            ->where('current_stock', '>', 0)
            ->orderBy('current_stock')
            ->get();
    }
    
    /**
     * Get out of stock items
     */
    public function getOutOfStockItems()
    {
        return Inventory::with(['product', 'productVariant'])
            ->whereRaw('(current_stock - reserved_stock) <= 0')
            ->get();
    }
    
    /**
     * Generate unique batch number
     */
    private function generateBatchNumber($productId, $productVariantId = null)
    {
        $prefix = 'BATCH';
        $date = now()->format('Ymd');
        $productCode = str_pad($productId, 4, '0', STR_PAD_LEFT);
        $variantCode = $productVariantId ? str_pad($productVariantId, 3, '0', STR_PAD_LEFT) : '000';
        $random = strtoupper(Str::random(3));
        
        return "{$prefix}-{$date}-{$productCode}-{$variantCode}-{$random}";
    }
    
    /**
     * Get or create inventory record
     */
    private function getOrCreateInventory($productId, $productVariantId = null)
    {
        return Inventory::firstOrCreate(
            [
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
            ],
            [
                'current_stock' => 0,
                'reserved_stock' => 0,
                'low_stock_threshold' => 10,
            ]
        );
    }
    
    /**
     * Get available batches in FIFO order (oldest first)
     */
    private function getAvailableBatches($inventoryId)
    {
        return DB::table('inventory_movements')
            ->select([
                'batch_number',
                'cost_per_unit',
                DB::raw('SUM(quantity) as available_quantity'),
                DB::raw('MIN(movement_date) as oldest_date'),
                DB::raw('MAX(movement_date) as newest_date')
            ])
            ->where('inventory_id', $inventoryId)
            ->whereNotIn('batch_number', function($query) {
                $query->select('batch_number')
                      ->from('inventory_movements')
                      ->where('batch_number', 'LIKE', 'RESERVED-%')
                      ->orWhere('batch_number', 'LIKE', 'RELEASED-%')
                      ->orWhere('batch_number', 'LIKE', 'ADJ-%');
            })
            ->groupBy('batch_number', 'cost_per_unit')
            ->having('available_quantity', '>', 0)
            ->orderBy('oldest_date', 'asc') // FIFO: oldest first
            ->get();
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
        $reason = 'Stock transfer'
    ) {
        DB::beginTransaction();
        
        try {
            // Get cost from source variant
            $stockDetails = $this->getStockDetails($fromProductId, $fromVariantId);
            $averageCost = $stockDetails['average_cost'];
            
            // Remove from source
            $this->removeStock($fromProductId, $fromVariantId, $quantity, $reason . ' (from)', 'transfer');
            
            // Add to destination
            $this->addStock($toProductId, $toVariantId, $quantity, $averageCost, $reason . ' (to)', 'transfer');
            
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
}