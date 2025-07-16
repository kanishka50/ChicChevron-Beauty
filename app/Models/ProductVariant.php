<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesInventory;

class ProductVariant extends Model
{
    use HasFactory, ManagesInventory;

    protected $fillable = [
        'product_id',
        'size',
        'color', 
        'scent',
        'sku',
        'name',
        'price',
        'cost_price',
        'discount_price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getEffectivePriceAttribute()
    {
        if ($this->discount_price && $this->discount_price < $this->price) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function getAvailableStockAttribute()
    {
        $inventory = $this->inventory;
        if (!$inventory) return 0;
        
        return max(0, $inventory->current_stock - $inventory->reserved_stock);
    }

    public function getProfitMarginAttribute()
    {
        if ($this->cost_price <= 0) return 0;
        
        return round((($this->price - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
 * Get display name for variant (formatted attributes)
 */
public function getDisplayNameAttribute()
{
    if ($this->name) {
        return $this->name;
    }
    
    $parts = array_filter([
        $this->size,
        $this->color,
        $this->scent
    ]);
    
    return !empty($parts) ? implode(' - ', $parts) : 'Standard';
}

/**
 * Get stock level percentage for visual indicators
 */
public function getStockLevelPercentageAttribute()
{
    if (!$this->inventory) return 0;
    
    $availableStock = $this->available_stock;
    $threshold = $this->inventory->low_stock_threshold;
    $referenceStock = $threshold * 3; // Consider "full" as 3x threshold
    
    if ($referenceStock == 0) return 0;
    
    return min(100, round(($availableStock / $referenceStock) * 100));
}

/**
 * Get stock status class for styling
 */
public function getStockStatusClassAttribute()
{
    if (!$this->inventory) return 'bg-gray-200';
    
    $percentage = $this->stock_level_percentage;
    
    if ($percentage == 0) {
        return 'bg-red-500';
    } elseif ($percentage <= 20) {
        return 'bg-orange-500';
    } elseif ($percentage <= 50) {
        return 'bg-yellow-500';
    } else {
        return 'bg-green-500';
    }
}

/**
 * Check if variant is in stock
 */
public function getInStockAttribute()
{
    return $this->available_stock > 0;
}

/**
 * Scope to get variants in stock
 */
public function scopeInStock($query)
{
    return $query->whereHas('inventory', function ($q) {
        $q->whereRaw('(current_stock - reserved_stock) > 0');
    });
}
}