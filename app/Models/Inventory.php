<?php

namespace App\Models;

use App\Traits\ManagesInventory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory, ManagesInventory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'variant_combination_id',
        'current_stock',
        'reserved_stock',
        'low_stock_threshold',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'reserved_stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant combination.
     */
    public function variantCombination()
    {
        return $this->belongsTo(VariantCombination::class);
    }

    /**
     * Get the inventory movements.
     */
    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Get available stock (current - reserved).
     */
    public function getAvailableStockAttribute()
    {
        return max(0, $this->current_stock - $this->reserved_stock);
    }

    /**
     * Check if stock is low.
     */
    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->low_stock_threshold;
    }

    /**
     * Get stock level percentage.
     */
    public function getStockLevelPercentageAttribute()
    {
        if ($this->low_stock_threshold <= 0) {
            return 100;
        }

        // Consider 3x threshold as full stock
        $fullStock = $this->low_stock_threshold * 3;
        return min(100, round(($this->current_stock / $fullStock) * 100));
    }

    /**
     * Get stock level status.
     */
    public function getStockLevelStatusAttribute()
    {
        $percentage = $this->stock_level_percentage;

        if ($percentage > 50) {
            return 'high';
        } elseif ($percentage > 20) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Get stock level color.
     */
    public function getStockLevelColorAttribute()
    {
        return [
            'high' => 'green',
            'medium' => 'yellow',
            'low' => 'red',
        ][$this->stock_level_status];
    }

    /**
     * Record stock movement.
     */
    public function recordMovement($type, $quantity, $batchNumber, $costPerUnit = null, $reason = null, $referenceType = null, $referenceId = null)
    {
        return $this->movements()->create([
            'batch_number' => $batchNumber,
            'movement_type' => $type,
            'quantity' => $quantity,
            'cost_per_unit' => $costPerUnit,
            'reason' => $reason,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'movement_date' => now(),
        ]);
    }

    /**
     * Add stock.
     */
    public function addStock($quantity, $batchNumber, $costPerUnit = null, $reason = null)
    {
        $this->increment('current_stock', $quantity);
        return $this->recordMovement('in', $quantity, $batchNumber, $costPerUnit, $reason);
    }

    /**
     * Remove stock.
     */
    public function removeStock($quantity, $batchNumber = null, $reason = null, $referenceType = null, $referenceId = null)
    {
        $this->decrement('current_stock', $quantity);
        return $this->recordMovement('out', $quantity, $batchNumber ?? 'FIFO', null, $reason, $referenceType, $referenceId);
    }

    /**
     * Reserve stock.
     */
    public function reserveStock($quantity)
    {
        if ($this->available_stock < $quantity) {
            return false;
        }

        $this->increment('reserved_stock', $quantity);
        return true;
    }

    /**
     * Release reserved stock.
     */
    public function releaseStock($quantity)
    {
        $this->decrement('reserved_stock', min($quantity, $this->reserved_stock));
    }

    /**
     * Scope for low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= low_stock_threshold');
    }

    /**
     * Scope for out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', 0);
    }
}