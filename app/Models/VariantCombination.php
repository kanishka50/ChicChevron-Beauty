<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesInventory;

class VariantCombination extends Model
{
    use HasFactory;
    use ManagesInventory;

    protected $fillable = [
        'product_id',
        'size_variant_id',
        'color_variant_id',
        'scent_variant_id',
        'combination_sku',
        'combination_price',
        'combination_cost_price',
    ];

    protected $casts = [
        'combination_price' => 'decimal:2',
        'combination_cost_price' => 'decimal:2',
    ];

    /**
     * Get the product that owns the combination.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size variant.
     */
    public function sizeVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'size_variant_id');
    }

    /**
     * Get the color variant.
     */
    public function colorVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'color_variant_id');
    }

    /**
     * Get the scent variant.
     */
    public function scentVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'scent_variant_id');
    }

    /**
     * Get the inventory for this combination.
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get cart items for this combination.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this combination.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the variant details as a string.
     */
    public function getVariantDetailsAttribute()
    {
        $details = [];

        if ($this->sizeVariant) {
            $details[] = 'Size: ' . $this->sizeVariant->variant_value;
        }

        if ($this->colorVariant) {
            $details[] = 'Color: ' . $this->colorVariant->variant_value;
        }

        if ($this->scentVariant) {
            $details[] = 'Scent: ' . $this->scentVariant->variant_value;
        }

        return implode(', ', $details);
    }

    /**
     * Get the variant details as an array.
     */
    public function getVariantArrayAttribute()
    {
        $variants = [];

        if ($this->sizeVariant) {
            $variants['size'] = $this->sizeVariant->variant_value;
        }

        if ($this->colorVariant) {
            $variants['color'] = $this->colorVariant->variant_value;
        }

        if ($this->scentVariant) {
            $variants['scent'] = $this->scentVariant->variant_value;
        }

        return $variants;
    }

    /**
     * Get the profit margin for this combination.
     */
    public function getProfitMarginAttribute()
    {
        if ($this->combination_cost_price <= 0) {
            return 0;
        }

        return round((($this->combination_price - $this->combination_cost_price) / $this->combination_price) * 100, 2);
    }

    /**
     * Check if combination is in stock.
     */
    public function getInStockAttribute()
    {
        return $this->inventory && $this->inventory->current_stock > 0;
    }




     /**
     * Get available stock for this combination
     */
    public function getAvailableStockAttribute()
    {
        $inventory = $this->inventory;
        return $inventory ? ($inventory->current_stock - $inventory->reserved_stock) : 0;
    }
    
    /**
     * Check if this combination is low on stock
     */
    public function getIsLowStockAttribute()
    {
        $inventory = $this->inventory;
        if (!$inventory) return false;
        $available = $inventory->current_stock - $inventory->reserved_stock;
        return $available <= $inventory->low_stock_threshold && $available > 0;
    }
    
    /**
     * Check if this combination is out of stock
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->available_stock <= 0;
    }
}