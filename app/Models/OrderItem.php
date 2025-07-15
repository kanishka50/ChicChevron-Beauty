<?php

// ===== OrderItem Model =====
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id', // CHANGED from variant_combination_id
        'product_name',
        'product_sku',
        'variant_details',
        'quantity',
        'unit_price',
        'cost_price',
        'discount_amount',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the order that owns this item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get formatted unit price.
     */
    public function getUnitPriceFormattedAttribute()
    {
        return 'Rs. ' . number_format($this->unit_price, 2);
    }

    /**
     * Get formatted total price.
     */
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rs. ' . number_format($this->total_price, 2);
    }

    /**
     * Get decoded variant details.
     */
    public function getVariantDetailsArrayAttribute()
    {
        return $this->variant_details ? json_decode($this->variant_details, true) : [];
    }

    /**
     * Get profit amount for this item.
     */
    public function getProfitAmountAttribute()
    {
        return ($this->unit_price - $this->cost_price) * $this->quantity;
    }

    /**
     * Get profit margin percentage.
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price <= 0) {
            return 0;
        }
        
        return round((($this->unit_price - $this->cost_price) / $this->cost_price) * 100, 2);
    }
}