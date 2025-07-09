<?php

namespace App\Traits;

use App\Services\InventoryService;

trait ManagesInventory
{
    /**
     * Get inventory service instance
     */
    protected function inventoryService()
    {
        return app(InventoryService::class);
    }
    
    /**
     * Add stock to product/variant
     */
    public function addStock($quantity, $costPerUnit, $reason = 'Stock received')
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->addStock(
            $productId,
            $variantCombinationId,
            $quantity,
            $costPerUnit,
            $reason
        );
    }
    
    /**
     * Remove stock using FIFO
     */
    public function removeStock($quantity, $reason = 'Stock sold', $referenceType = null, $referenceId = null)
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->removeStock(
            $productId,
            $variantCombinationId,
            $quantity,
            $reason,
            $referenceType,
            $referenceId
        );
    }
    
    /**
     * Reserve stock for orders
     */
    public function reserveStock($quantity, $referenceType = 'order', $referenceId = null)
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->reserveStock(
            $productId,
            $variantCombinationId,
            $quantity,
            $referenceType,
            $referenceId
        );
    }
    
    /**
     * Release reserved stock
     */
    public function releaseReservedStock($quantity, $referenceType = 'order', $referenceId = null)
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->releaseReservedStock(
            $productId,
            $variantCombinationId,
            $quantity,
            $referenceType,
            $referenceId
        );
    }
    
    /**
     * Confirm reserved stock (convert to sale)
     */
    public function confirmReservedStock($quantity, $referenceType = 'order', $referenceId = null)
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->confirmReservedStock(
            $productId,
            $variantCombinationId,
            $quantity,
            $referenceType,
            $referenceId
        );
    }
    
    /**
     * Adjust stock manually
     */
    public function adjustStock($newQuantity, $reason = 'Manual adjustment')
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->adjustStock(
            $productId,
            $variantCombinationId,
            $newQuantity,
            $reason
        );
    }
    
    /**
     * Get detailed stock information
     */
    public function getStockDetails()
    {
        $productId = $this->product_id ?? $this->id;
        $variantCombinationId = $this->variant_combination_id ?? null;
        
        return $this->inventoryService()->getStockDetails($productId, $variantCombinationId);
    }
    
    /**
     * Check if product/variant has sufficient stock
     */
    public function hasSufficientStock($quantity)
    {
        $details = $this->getStockDetails();
        return $details['available_stock'] >= $quantity;
    }
    
    /**
     * Get available stock (current - reserved)
     */
    public function getAvailableStock()
    {
        $details = $this->getStockDetails();
        return $details['available_stock'];
    }
    
    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        $details = $this->getStockDetails();
        $inventory = $details['inventory'];
        
        return $details['available_stock'] <= $inventory->low_stock_threshold;
    }
    
    /**
     * Check if out of stock
     */
    public function isOutOfStock()
    {
        return $this->getAvailableStock() <= 0;
    }
    
    /**
     * Get stock status for display
     */
    public function getStockStatus()
    {
        $availableStock = $this->getAvailableStock();
        $details = $this->getStockDetails();
        $threshold = $details['inventory']->low_stock_threshold;
        
        if ($availableStock <= 0) {
            return [
                'status' => 'out-of-stock',
                'label' => 'Out of Stock',
                'class' => 'bg-red-100 text-red-800',
                'percentage' => 0
            ];
        } elseif ($availableStock <= $threshold * 0.5) {
            return [
                'status' => 'critical',
                'label' => 'Critical Stock',
                'class' => 'bg-orange-100 text-orange-800',
                'percentage' => ($availableStock / ($threshold * 2)) * 100
            ];
        } elseif ($availableStock <= $threshold) {
            return [
                'status' => 'low',
                'label' => 'Low Stock',
                'class' => 'bg-yellow-100 text-yellow-800',
                'percentage' => ($availableStock / ($threshold * 2)) * 100
            ];
        } else {
            return [
                'status' => 'good',
                'label' => 'In Stock',
                'class' => 'bg-green-100 text-green-800',
                'percentage' => min(100, ($availableStock / ($threshold * 2)) * 100)
            ];
        }
    }
}