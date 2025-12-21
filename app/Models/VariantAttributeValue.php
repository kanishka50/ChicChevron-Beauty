<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariantAttributeValue extends Model
{
    use HasFactory;

    /**
     * Only has created_at, no updated_at
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'product_variant_id',
        'attribute_option_id',
    ];

    /**
     * Get the variant that owns this value.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the option for this value.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }

    /**
     * Get the attribute name through relationships.
     */
    public function getAttributeNameAttribute(): string
    {
        return $this->option?->productAttribute?->attribute_name ?? '';
    }

    /**
     * Get the option value.
     */
    public function getValueAttribute(): string
    {
        return $this->option?->value ?? '';
    }
}
