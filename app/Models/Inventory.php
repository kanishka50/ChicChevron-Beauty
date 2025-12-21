<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_variant_id',
        'stock_quantity',
        'reserved_quantity',
        'low_stock_threshold',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    /**
     * Get the product variant this inventory belongs to.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Alias for variant() for backwards compatibility.
     */
    public function productVariant(): BelongsTo
    {
        return $this->variant();
    }

    /**
     * Get all movements for this inventory (via product_variant_id).
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'product_variant_id', 'product_variant_id');
    }

    /**
     * Get available stock (stock - reserved).
     */
    public function getAvailableStockAttribute(): int
    {
        return $this->stock_quantity - $this->reserved_quantity;
    }

    /**
     * Check if stock is low.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->available_stock <= $this->low_stock_threshold && $this->available_stock > 0;
    }

    /**
     * Check if out of stock.
     */
    public function getIsOutOfStockAttribute(): bool
    {
        return $this->available_stock <= 0;
    }

    /**
     * Get stock status for display.
     */
    public function getStockStatusAttribute(): string
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
     * Get stock status color for display.
     */
    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out-of-stock' => 'red',
            'critical' => 'orange',
            'low' => 'yellow',
            default => 'green',
        };
    }

    /**
     * Get stock percentage for visual indicators.
     */
    public function getStockPercentageAttribute(): int
    {
        if ($this->stock_quantity <= 0) {
            return 0;
        }

        $referenceStock = $this->low_stock_threshold * 3;
        $percentage = ($this->available_stock / $referenceStock) * 100;

        return min(100, round($percentage));
    }

    /**
     * Scope for low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(stock_quantity - reserved_quantity) <= low_stock_threshold')
                     ->whereRaw('(stock_quantity - reserved_quantity) > 0');
    }

    /**
     * Scope for out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereRaw('(stock_quantity - reserved_quantity) <= 0');
    }

    /**
     * Scope for in stock items.
     */
    public function scopeInStock($query)
    {
        return $query->whereRaw('(stock_quantity - reserved_quantity) > 0');
    }

    /**
     * Reserve stock for an order.
     */
    public function reserveStock(int $quantity): bool
    {
        if ($this->available_stock < $quantity) {
            return false;
        }

        $this->reserved_quantity += $quantity;
        $this->save();

        return true;
    }

    /**
     * Release reserved stock.
     */
    public function releaseStock(int $quantity): void
    {
        $this->reserved_quantity = max(0, $this->reserved_quantity - $quantity);
        $this->save();
    }

    /**
     * Deduct stock (convert reserved to sold).
     */
    public function deductStock(int $quantity): void
    {
        $this->stock_quantity = max(0, $this->stock_quantity - $quantity);
        $this->reserved_quantity = max(0, $this->reserved_quantity - $quantity);
        $this->save();
    }

    /**
     * Add stock.
     */
    public function addStock(int $quantity): void
    {
        $this->stock_quantity += $quantity;
        $this->save();
    }
}