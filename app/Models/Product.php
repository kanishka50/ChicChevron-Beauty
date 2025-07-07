<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sku',
        'brand_id',
        'category_id',
        'product_type_id',
        'texture_id',
        'cost_price',
        'selling_price',
        'discount_price',
        'main_image',
        'how_to_use',
        'suitable_for',
        'fragrance',
        'has_variants',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'has_variants' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function texture()
    {
        return $this->belongsTo(Texture::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variantCombinations()
    {
        return $this->hasMany(VariantCombination::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->discount_price ?? $this->selling_price;
    }

    public function getProfitMarginAttribute()
    {
        $sellingPrice = $this->current_price;
        $costPrice = $this->cost_price;
        
        if ($costPrice > 0) {
            return round((($sellingPrice - $costPrice) / $costPrice) * 100, 2);
        }
        
        return 0;
    }
}