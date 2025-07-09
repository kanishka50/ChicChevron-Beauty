<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
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
     * Get the product this inventory belongs to
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get the variant combination this inventory belongs to
     */
    public function variantCombination(): BelongsTo
    {
        return $this->belongsTo(VariantCombination::class);
    }
    
    /**
     * Get all movements for this inventory
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }
    
    /**
     * Get available stock (current - reserved)
     */
    public function getAvailableStockAttribute()
    {
        return $this->current_stock - $this->reserved_stock;
    }
    
    /**
     * Check if stock is low
     */
    public function getIsLowStockAttribute()
    {
        return $this->available_stock <= $this->low_stock_threshold && $this->available_stock > 0;
    }
    
    /**
     * Check if out of stock
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->available_stock <= 0;
    }
    
    /**
     * Get stock status for display
     */
    public function getStockStatusAttribute()
    {
        if ($this->is_out_of_stock) {
            return 'out-of-stock';
        } elseif ($this->available_stock <= $this->low_stock_threshold * 0.5) {
            return 'critical';
        } elseif ($this->is_low_stock) {
            return 'low';
        } else {
            return 'good';
        }
    }
}