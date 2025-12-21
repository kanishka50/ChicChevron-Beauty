<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    /**
     * Movement types:
     * - in: Stock added (purchase, restock)
     * - reserved: Stock reserved for pending order
     * - sold: Stock sold (order completed)
     * - released: Reserved stock released (order cancelled)
     * - adjustment: Manual stock adjustment
     */
    public const TYPE_IN = 'in';
    public const TYPE_RESERVED = 'reserved';
    public const TYPE_SOLD = 'sold';
    public const TYPE_RELEASED = 'released';
    public const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'product_variant_id',
        'type',
        'quantity',
        'cost_per_unit',
        'order_id',
        'supplier',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost_per_unit' => 'decimal:2',
    ];

    /**
     * Get the variant this movement belongs to.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the order associated with this movement (if any).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get total value of this movement.
     */
    public function getTotalValueAttribute(): float
    {
        return abs($this->quantity) * ($this->cost_per_unit ?? 0);
    }

    /**
     * Check if this is an incoming movement.
     */
    public function getIsIncomingAttribute(): bool
    {
        return $this->type === self::TYPE_IN ||
               ($this->type === self::TYPE_ADJUSTMENT && $this->quantity > 0);
    }

    /**
     * Check if this is an outgoing movement.
     */
    public function getIsOutgoingAttribute(): bool
    {
        return $this->type === self::TYPE_SOLD ||
               ($this->type === self::TYPE_ADJUSTMENT && $this->quantity < 0);
    }

    /**
     * Get type label for display.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_IN => 'Stock In',
            self::TYPE_RESERVED => 'Reserved',
            self::TYPE_SOLD => 'Sold',
            self::TYPE_RELEASED => 'Released',
            self::TYPE_ADJUSTMENT => 'Adjustment',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get type color for Filament badges.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_IN => 'success',
            self::TYPE_RESERVED => 'warning',
            self::TYPE_SOLD => 'danger',
            self::TYPE_RELEASED => 'info',
            self::TYPE_ADJUSTMENT => 'gray',
            default => 'gray',
        };
    }

    /**
     * Scope for movements by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for movements related to an order.
     */
    public function scopeForOrder($query, int $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
