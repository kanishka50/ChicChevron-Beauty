<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AttributeOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_attribute_id',
        'value',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    /**
     * Get the product attribute that owns this option.
     */
    public function productAttribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    /**
     * Get the variants that use this option.
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'variant_attribute_values',
            'attribute_option_id',
            'product_variant_id'
        );
    }

    /**
     * Get the attribute name through the relationship.
     */
    public function getAttributeNameAttribute(): string
    {
        return $this->productAttribute?->attribute_name ?? '';
    }

    /**
     * Get the product through the relationship.
     */
    public function getProductAttribute(): ?Product
    {
        return $this->productAttribute?->product;
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
