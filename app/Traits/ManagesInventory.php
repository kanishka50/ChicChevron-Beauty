<?php

namespace App\Traits;

use App\Models\InventoryMovement;

trait ManagesInventory
{
    public function addStock($quantity, $batchNumber, $costPerUnit = null, $reason = 'Purchase')
    {
        $this->increment('current_stock', $quantity);
        
        return InventoryMovement::create([
            'inventory_id' => $this->id,
            'batch_number' => $batchNumber,
            'movement_type' => 'in',
            'quantity' => $quantity,
            'cost_per_unit' => $costPerUnit,
            'reason' => $reason,
            'movement_date' => now(),
        ]);
    }

    public function removeStock($quantity, $reason = 'Sale', $referenceType = null, $referenceId = null)
    {
        if ($this->available_stock < $quantity) {
            throw new \Exception('Insufficient stock');
        }

        $this->increment('current_stock', -$quantity);
        
        return InventoryMovement::create([
            'inventory_id' => $this->id,
            'batch_number' => $this->getOldestBatch(),
            'movement_type' => 'out',
            'quantity' => $quantity,
            'reason' => $reason,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'movement_date' => now(),
        ]);
    }

    public function reserveStock($quantity)
    {
        if ($this->available_stock < $quantity) {
            throw new \Exception('Insufficient stock');
        }

        $this->increment('reserved_stock', $quantity);
    }

    public function releaseStock($quantity)
    {
        $this->decrement('reserved_stock', $quantity);
    }

    public function getAvailableStockAttribute()
    {
        return $this->current_stock - $this->reserved_stock;
    }

    public function getStockStatusAttribute()
    {
        $percentage = ($this->current_stock / max($this->low_stock_threshold * 5, 1)) * 100;
        
        if ($percentage > 50) {
            return 'in_stock';
        } elseif ($percentage > 20) {
            return 'low_stock';
        } else {
            return 'critical';
        }
    }

    protected function getOldestBatch()
    {
        return $this->movements()
            ->where('movement_type', 'in')
            ->where('quantity', '>', 0)
            ->orderBy('movement_date')
            ->value('batch_number') ?? 'DEFAULT';
    }
}