<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_type',
        'variant_value',
        'sku_suffix',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant combinations where this variant is used as size.
     */
    public function sizeInCombinations()
    {
        return $this->hasMany(VariantCombination::class, 'size_variant_id');
    }

    /**
     * Get the variant combinations where this variant is used as color.
     */
    public function colorInCombinations()
    {
        return $this->hasMany(VariantCombination::class, 'color_variant_id');
    }

    /**
     * Get the variant combinations where this variant is used as scent.
     */
    public function scentInCombinations()
    {
        return $this->hasMany(VariantCombination::class, 'scent_variant_id');
    }

    /**
     * Get the full SKU for this variant.
     */
    public function getFullSkuAttribute()
    {
        return $this->product->sku . '-' . $this->sku_suffix;
    }


    /**
     * Scope for active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for variants by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('variant_type', $type);
    }
}