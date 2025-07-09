<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [
        'inventory_id',
        'batch_number',
        'movement_type',
        'quantity',
        'cost_per_unit',
        'reason',
        'reference_type',
        'reference_id',
        'movement_date',
    ];
    
    protected $casts = [
        'quantity' => 'integer',
        'cost_per_unit' => 'decimal:2',
        'reference_id' => 'integer',
        'movement_date' => 'datetime',
    ];
    
    /**
     * Get the inventory this movement belongs to
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
    
    /**
     * Get total value of this movement
     */
    public function getTotalValueAttribute()
    {
        return abs($this->quantity) * ($this->cost_per_unit ?? 0);
    }
    
    /**
     * Check if this is an incoming movement
     */
    public function getIsIncomingAttribute()
    {
        return $this->movement_type === 'in' || ($this->movement_type === 'adjustment' && $this->quantity > 0);
    }
    
    /**
     * Check if this is an outgoing movement
     */
    public function getIsOutgoingAttribute()
    {
        return $this->movement_type === 'out' || ($this->movement_type === 'adjustment' && $this->quantity < 0);
    }
}