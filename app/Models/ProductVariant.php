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

    /**
     * Get the product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the inventory for this variant.
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get cart items for this variant.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this variant.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the effective price (considering discount).
     */
    public function getEffectivePriceAttribute()
    {
        if ($this->discount_price && $this->discount_price < $this->price) {
            return $this->discount_price;
        }
        return $this->price;
    }

    /**
     * Get available stock.
     */
    public function getAvailableStockAttribute()
    {
        $inventory = $this->inventory;
        if (!$inventory) return 0;
        
        return max(0, $inventory->current_stock - $inventory->reserved_stock);
    }

    /**
     * Scope for active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get profit margin percentage.
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price <= 0) return 0;
        
        return round((($this->price - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    /**
     * Check if variant has specific attribute
     */
    public function hasAttribute($type)
    {
        return !empty($this->$type);
    }

    /**
     * Get variant attributes as array
     */
    public function getAttributesArray()
    {
        $attributes = [];
        
        if ($this->size) $attributes['size'] = $this->size;
        if ($this->color) $attributes['color'] = $this->color;
        if ($this->scent) $attributes['scent'] = $this->scent;
        
        return $attributes;
    }
}