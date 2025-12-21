<?php
// ===== CartItem Model =====
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
        'product_variant_id', // CHANGED from variant_combination_id
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
     * Get the product variant associated with the cart item.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
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
        if (!$this->productVariant) {
            return null;
        }

        return $this->productVariant->name;
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

        if (!$this->productVariant || !$this->productVariant->is_active) {
            return false;
        }

        // Inventory table only has product_variant_id, not product_id
        $inventory = Inventory::where('product_variant_id', $this->product_variant_id)->first();

        if (!$inventory) {
            return false;
        }

        $availableStock = $inventory->stock_quantity - $inventory->reserved_quantity;
        return $availableStock >= $this->quantity;
    }

    /**
     * Get available stock for this cart item's product/variant.
     */
    public function getAvailableStockAttribute()
    {
        // Inventory table only has product_variant_id, not product_id
        $inventory = Inventory::where('product_variant_id', $this->product_variant_id)->first();

        if (!$inventory) {
            return 0;
        }

        return max(0, $inventory->stock_quantity - $inventory->reserved_quantity);
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