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
}