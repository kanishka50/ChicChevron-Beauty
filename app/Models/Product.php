<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasSlug;

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
        'views_count' => 'integer',
    ];

    /**
     * Get the sluggable field for the trait.
     */
    public function getSlugSourceField(): string
    {
        return 'name';
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product type.
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the texture.
     */
    public function texture()
    {
        return $this->belongsTo(Texture::class);
    }

    /**
     * Get the product images.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the product ingredients.
     */
    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }

    /**
     * Get the product colors.
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors');
    }

    /**
     * Get the product variants.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the variant combinations.
     */
    public function variantCombinations()
    {
        return $this->hasMany(VariantCombination::class);
    }

    /**
     * Get the inventory records.
     */
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get approved reviews.
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get the promotions.
     */
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products');
    }

    /**
     * Get active promotions.
     */
    public function activePromotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products')
            ->where('is_active', true);
    }



    /**
     * Check if product is in stock.
     */
    public function getInStockAttribute()
    {
        if ($this->has_variants) {
            return $this->inventory()->where('current_stock', '>', 0)->exists();
        }

        return $this->inventory()->where('variant_combination_id', null)
            ->where('current_stock', '>', 0)->exists();
    }

    /**
     * Get total stock across all variants.
     */
    public function getTotalStockAttribute()
    {
        return $this->inventory()->sum('current_stock');
    }

    /**
     * Get average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Get review count.
     */
    public function getReviewCountAttribute()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount()
    {
        $this->increment('views_count');
    }

    /**
     * Scope for active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for products with stock.
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('inventory', function ($q) {
            $q->where('current_stock', '>', 0);
        });
    }


    /**
     * Get the current price (considering discount).
     * discount_price should be treated as discount AMOUNT, not final price
     */
    public function getCurrentPriceAttribute()
    {
        if ($this->discount_price && $this->discount_price > 0) {
            // discount_price is the discount AMOUNT to subtract
            $finalPrice = $this->selling_price - $this->discount_price;
            // Make sure final price doesn't go below 0
            return max(0, $finalPrice);
        }
        
        return $this->selling_price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->discount_price || $this->discount_price <= 0) {
            return 0;
        }

        // Calculate percentage based on discount amount
        return round(($this->discount_price / $this->selling_price) * 100, 2);
    }

    /**
     * Get the profit margin.
     * Formula: ((Selling Price - Cost Price) / Cost Price) * 100
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price <= 0) {
            return 0;
        }

        $currentPrice = $this->current_price;
        
        // Correct formula: profit margin based on cost price
        return round((($currentPrice - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    /**
     * Get the profit amount.
     */
    public function getProfitAmountAttribute()
    {
        return max(0, $this->current_price - $this->cost_price);
    }

    /**
     * Check if product is profitable.
     */
    public function getIsProfitableAttribute()
    {
        return $this->current_price > $this->cost_price;
    }
}