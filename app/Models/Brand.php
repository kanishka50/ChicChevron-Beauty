<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the sluggable field for the trait.
     */
    public function getSlugSourceField(): string
    {
        return 'name';
    }

    /**
     * Get the products for the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products for the brand.
     */
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    /**
     * Get the product count.
     */
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    /**
     * Scope for active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for brands with products.
     */
    public function scopeHasProducts($query)
    {
        return $query->has('products');
    }
}