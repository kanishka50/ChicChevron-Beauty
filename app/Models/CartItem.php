<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variant_combination_id',
        'quantity',
        'unit_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant combination associated with the cart item.
     */
    public function variantCombination()
    {
        return $this->belongsTo(VariantCombination::class);
    }

    /**
     * Calculate total price for this cart item.
     */
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Get formatted total price.
     */
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rs. ' . number_format($this->total_price, 2);
    }

    /**
     * Get formatted unit price.
     */
    public function getUnitPriceFormattedAttribute()
    {
        return 'Rs. ' . number_format($this->unit_price, 2);
    }

    /**
     * Get variant details in a readable format.
     */
    public function getVariantDetailsFormattedAttribute()
    {
        if (!$this->variantCombination) {
            return null;
        }

        $details = [];
        
        if ($this->variantCombination->sizeVariant) {
            $details[] = 'Size: ' . $this->variantCombination->sizeVariant->variant_value;
        }
        
        if ($this->variantCombination->colorVariant) {
            $details[] = 'Color: ' . $this->variantCombination->colorVariant->variant_value;
        }
        
        if ($this->variantCombination->scentVariant) {
            $details[] = 'Scent: ' . $this->variantCombination->scentVariant->variant_value;
        }

        return implode(' | ', $details);
    }

    /**
     * Get product image URL.
     */
    public function getProductImageAttribute()
    {
        return $this->product->main_image 
            ? asset('storage/' . $this->product->main_image)
            : '/placeholder.jpg';
    }

    /**
     * Get product name with variant details.
     */
    public function getFullProductNameAttribute()
    {
        $name = $this->product->name;
        
        if ($this->variant_details_formatted) {
            $name .= ' (' . $this->variant_details_formatted . ')';
        }
        
        return $name;
    }

    /**
     * Check if the cart item is still available (product active and in stock).
     */
    public function getIsAvailableAttribute()
    {
        if (!$this->product || !$this->product->is_active) {
            return false;
        }

        $inventory = Inventory::where('product_id', $this->product_id)
                             ->where('variant_combination_id', $this->variant_combination_id)
                             ->first();

        if (!$inventory) {
            return false;
        }

        $availableStock = $inventory->current_stock - $inventory->reserved_stock;
        return $availableStock >= $this->quantity;
    }

    /**
     * Get available stock for this cart item's product/variant.
     */
    public function getAvailableStockAttribute()
    {
        $inventory = Inventory::where('product_id', $this->product_id)
                             ->where('variant_combination_id', $this->variant_combination_id)
                             ->first();

        if (!$inventory) {
            return 0;
        }

        return max(0, $inventory->current_stock - $inventory->reserved_stock);
    }

    /**
     * Scope to get cart items for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get cart items for a specific session.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope to get cart items for current user or session.
     */
    public function scopeForCurrentCart($query)
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            return $query->where('user_id', \Illuminate\Support\Facades\Auth::id());
        }
        
        return $query->where('session_id', \Illuminate\Support\Facades\Session::getId());
    }
}