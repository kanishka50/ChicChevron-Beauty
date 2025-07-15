<?php

// ===== Inventory Model =====
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventory';
    
    protected $fillable = [
        'product_id',
        'product_variant_id', // CHANGED from variant_combination_id
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
     * Get the product variant this inventory belongs to
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
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
    
    /**
     * Get stock status color for display
     */
    public function getStockStatusColorAttribute()
    {
        switch ($this->stock_status) {
            case 'out-of-stock':
                return 'red';
            case 'critical':
                return 'orange';
            case 'low':
                return 'yellow';
            default:
                return 'green';
        }
    }
    
    /**
     * Get stock percentage for visual indicators
     */
    public function getStockPercentageAttribute()
    {
        if ($this->current_stock <= 0) {
            return 0;
        }
        
        // Calculate based on low stock threshold as reference
        $referenceStock = $this->low_stock_threshold * 3; // Consider "full" as 3x low stock threshold
        $percentage = ($this->available_stock / $referenceStock) * 100;
        
        return min(100, round($percentage));
    }
    
    /**
     * Scope for low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(current_stock - reserved_stock) <= low_stock_threshold')
                     ->whereRaw('(current_stock - reserved_stock) > 0');
    }
    
    /**
     * Scope for out of stock items
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereRaw('(current_stock - reserved_stock) <= 0');
    }
    
    /**
     * Scope for in stock items
     */
    public function scopeInStock($query)
    {
        return $query->whereRaw('(current_stock - reserved_stock) > 0');
    }
    
    /**
     * Reserve stock for an order
     */
    public function reserveStock($quantity)
    {
        if ($this->available_stock < $quantity) {
            return false;
        }
        
        $this->reserved_stock += $quantity;
        $this->save();
        
        return true;
    }
    
    /**
     * Release reserved stock
     */
    public function releaseStock($quantity)
    {
        $this->reserved_stock = max(0, $this->reserved_stock - $quantity);
        $this->save();
    }
    
    /**
     * Deduct stock (convert reserved to sold)
     */
    public function deductStock($quantity)
    {
        $this->current_stock = max(0, $this->current_stock - $quantity);
        $this->reserved_stock = max(0, $this->reserved_stock - $quantity);
        $this->save();
    }
    
    /**
     * Add stock
     */
    public function addStock($quantity)
    {
        $this->current_stock += $quantity;
        $this->save();
    }
}