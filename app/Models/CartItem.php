<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'variant_combination_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product.
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
     * Get the price for this cart item.
     */
    public function getPriceAttribute()
    {
        if ($this->variant_combination_id) {
            return $this->variantCombination->combination_price;
        }
        
        return $this->product->current_price;
    }

    /**
     * Get the subtotal for this cart item.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Check if item is in stock.
     */
    public function getInStockAttribute()
    {
        if ($this->variant_combination_id) {
            $inventory = $this->variantCombination->inventory;
            return $inventory && $inventory->available_stock >= $this->quantity;
        }
        
        $inventory = $this->product->inventory()->whereNull('variant_combination_id')->first();
        return $inventory && $inventory->available_stock >= $this->quantity;
    }
}