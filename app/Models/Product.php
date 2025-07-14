<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesInventory;

class Product extends Model
{
    use HasFactory, HasSlug, ManagesInventory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sku',
        'brand_id',
        'category_id',
        'product_type_id',
        'texture_id',
        'main_image',
        'how_to_use',
        'suitable_for',
        'fragrance',
        'is_active',
        'views_count',
        'average_rating',
        'reviews_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
    ];
    

    /**
     * Get the sluggable field for the trait.
     */
    public function getSlugSourceField(): string
    {
        return 'name';
    }

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

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
     * Get the inventory record.
     */
    public function inventory()
    {
        return $this->hasOne(Inventory::class)->whereNull('variant_combination_id');
    }

    /**
     * Get all inventory records (including variants).
     */
    public function allInventory()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get order items.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get cart items.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get wishlist items.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // =====================================================
    // QUERY SCOPES
    // =====================================================

    /**
     * Scope to get only active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only featured products.
     */
    public function scopeFeatured($query)
    {
        // This assumes you have a featured flag or can determine featured products
        // For now, we'll use products with reviews or high view count
        return $query->where(function ($q) {
            $q->whereHas('reviews')
              ->orWhere('views_count', '>', 100);
        });
    }

    /**
     * Scope to get products that are in stock.
     */
    public function scopeInStock($query)
    {
        return $query->whereHas('allInventory', function ($inventoryQuery) {
            $inventoryQuery->whereRaw('(current_stock - reserved_stock) > 0');
        });
    }

    /**
     * Scope to get products that are out of stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereDoesntHave('allInventory', function ($inventoryQuery) {
            $inventoryQuery->whereRaw('(current_stock - reserved_stock) > 0');
        });
    }

    /**
     * Scope to search products by keyword.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('sku', 'like', "%{$keyword}%")
              ->orWhereHas('brand', function ($brandQuery) use ($keyword) {
                  $brandQuery->where('name', 'like', "%{$keyword}%");
              })
              ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                  $categoryQuery->where('name', 'like', "%{$keyword}%");
              })
              ->orWhereHas('ingredients', function ($ingredientQuery) use ($keyword) {
                  $ingredientQuery->where('ingredient_name', 'like', "%{$keyword}%");
              });
        });
    }

    // =====================================================
    // ACCESSOR METHODS
    // =====================================================

    /**
     * Get the main image URL.
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        return asset('images/placeholder-product.jpg');
    }

    

    /**
     * Check if product is in stock.
     */
    public function getInStockAttribute()
    {
        return $this->getStockLevel() > 0;
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    /**
     * Get the total review count.
     */
    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }



    /**
     * Check if product is new (created within last 30 days).
     */
    public function getIsNewAttribute()
    {
        return $this->created_at->isAfter(now()->subDays(30));
    }

    /**
     * Get product URL.
     */
    public function getUrlAttribute()
    {
        return route('products.show', $this->slug);
    }

    /**
     * Get price range for variant products.
     */
    public function getPriceRangeAttribute()
{
    $prices = $this->variantCombinations()
        ->where('is_active', true)
        ->pluck('combination_price');

    if ($prices->isEmpty()) {
        return ['min' => 0, 'max' => 0];
    }

    return [
        'min' => $prices->min(),
        'max' => $prices->max(),
    ];
}


/**
 * Get effective price range (considering discounts)
 */
public function getEffectivePriceRangeAttribute()
{
    $effectivePrices = $this->variantCombinations()
        ->where('is_active', true)
        ->get()
        ->map(function ($combination) {
            return $combination->effective_price;
        });

    if ($effectivePrices->isEmpty()) {
        return ['min' => 0, 'max' => 0];
    }

    return [
        'min' => $effectivePrices->min(),
        'max' => $effectivePrices->max(),
    ];
}

    /**
     * Get formatted price range.
     */
    public function getFormattedPriceRangeAttribute()
    {
        $range = $this->price_range;
        
        if ($range['min'] == $range['max']) {
            return 'Rs. ' . number_format($range['min'], 2);
        }
        
        return 'Rs. ' . number_format($range['min'], 2) . ' - Rs. ' . number_format($range['max'], 2);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Check if product has active promotions.
     */
    public function hasActivePromotions()
    {
        return $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }

    /**
     * Get active promotion discount.
     */
    public function getActivePromotionDiscount()
    {
        $promotion = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        return $promotion ? $promotion->discount_percentage : 0;
    }

    /**
     * Check if product can be reviewed by user.
     */
    public function canBeReviewedBy($user)
    {
        if (!$user) {
            return false;
        }

        // Check if user has purchased this product
        return $this->orderItems()
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', 'completed');
            })
            ->exists();
    }

    /**
     * Get related products.
     */
    public function getRelatedProducts($limit = 8)
    {
        return static::active()
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('category_id', $this->category_id)
                      ->orWhere('brand_id', $this->brand_id);
            })
            ->inStock()
            ->limit($limit)
            ->get();
    }

    /**
     * Get available variant combinations.
     */
    public function getAvailableVariantCombinations()
{
    // Remove has_variants check
    return $this->variantCombinations()
        ->with(['sizeVariant', 'colorVariant', 'scentVariant', 'inventory'])
        ->where('is_active', true)
        ->whereHas('inventory', function ($query) {
            $query->whereRaw('(current_stock - reserved_stock) > 0');
        })
        ->get();
}

    /**
     * Get variant options grouped by type.
     */
    public function getVariantOptions()
    {
        if (!$this->has_variants) {
            return [];
        }

        return [
            'sizes' => $this->variants()->where('variant_type', 'size')->get(),
            'colors' => $this->variants()->where('variant_type', 'color')->get(),
            'scents' => $this->variants()->where('variant_type', 'scent')->get(),
        ];
    }

    /**
     * Find variant combination by variant IDs.
     */
    public function findVariantCombination($sizeId = null, $colorId = null, $scentId = null)
    {
        return $this->variantCombinations()
            ->where(function ($query) use ($sizeId, $colorId, $scentId) {
                if ($sizeId) {
                    $query->where('size_variant_id', $sizeId);
                } else {
                    $query->whereNull('size_variant_id');
                }
                
                if ($colorId) {
                    $query->where('color_variant_id', $colorId);
                } else {
                    $query->whereNull('color_variant_id');
                }
                
                if ($scentId) {
                    $query->where('scent_variant_id', $scentId);
                } else {
                    $query->whereNull('scent_variant_id');
                }
            })
            ->first();
    }

    /**
     * Get SEO meta description.
     */
    public function getMetaDescriptionAttribute()
    {
        $description = strip_tags($this->description);
        return \Illuminate\Support\Str::limit($description, 160);
    }

    /**
     * Get structured data for SEO.
     */
    public function getStructuredDataAttribute()
    {
        $priceRange = $this->effective_price_range;
    
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $this->name,
        'description' => $this->meta_description,
        'sku' => $this->sku,
        'image' => $this->main_image_url,
        'url' => $this->url,
        'brand' => [
            '@type' => 'Brand',
            'name' => $this->brand->name ?? 'ChicChevron Beauty',
        ],
        'offers' => [
            '@type' => 'AggregateOffer',
            'lowPrice' => $priceRange['min'],
            'highPrice' => $priceRange['max'],
            'priceCurrency' => 'LKR',
            'availability' => $this->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller' => [
                '@type' => 'Organization',
                'name' => 'ChicChevron Beauty',
            ],
        ],
    ];

        if ($this->average_rating > 0) {
            $data['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $this->average_rating,
                'reviewCount' => $this->review_count,
            ];
        }

        return $data;
    }

    // =====================================================
    // STATIC METHODS
    // =====================================================

    /**
 * Get featured products for homepage.
 */
public static function getFeaturedProducts($limit = 8)
{
    return static::active()
        ->with(['brand', 'category', 'images', 'inventory'])
        ->featured()
        ->inStock()
        ->limit($limit)
        ->get();
}

    /**
 * Get new arrivals for homepage.
 */
public static function getNewArrivals($limit = 8)
{
    return static::active()
        ->with(['brand', 'category', 'images', 'inventory'])
        ->latest('created_at')
        ->inStock()
        ->limit($limit)
        ->get();
}

/**
 * Get best sellers for homepage.
 */
public static function getBestSellers($limit = 8)
{
    return static::active()
        ->with(['brand', 'category', 'images', 'inventory'])
        ->inStock()
        ->limit($limit)
        ->get();
}




    // =====================================================
    // CART-SPECIFIC METHODS (ADD TO EXISTING MODEL)
    // =====================================================

    /**
     * Check if product has any stock available (enhanced for cart)
     */
public function getStockLevel()
{
    // Remove the has_variants check since ALL products have variants
    return $this->variantCombinations()
        ->with('inventory')
        ->get()
        ->sum(function ($combination) {
            return $combination->inventory 
                ? ($combination->inventory->current_stock - $combination->inventory->reserved_stock)
                : 0;
        });
}

/**
 * Check if product has stock
 */
public function hasStock()
{
    return $this->getStockLevel() > 0;
}

    /**
     * Get available stock for specific variant or main product
     */
    public function getAvailableStock($variantCombinationId = null)
    {
        if ($variantCombinationId) {
            $inventory = $this->allInventory()
                             ->where('variant_combination_id', $variantCombinationId)
                             ->first();
        } else {
            $inventory = $this->inventory;
        }

        if (!$inventory) {
            return 0;
        }

        return max(0, $inventory->current_stock - $inventory->reserved_stock);
    }

    

    /**
     * Check if product can be added to cart
     */
    public function canBeAddedToCart($variantCombinationId = null, $quantity = 1)
    {
        // Check if product is active
        if (!$this->is_active) {
            return [
                'can_add' => false,
                'message' => 'This product is no longer available.'
            ];
        }

        // ALWAYS require variant combination
        if (!$variantCombinationId) {
            return [
                'can_add' => false,
                'message' => 'Please select product options.'
            ];
        }

        // Check stock availability
        $availableStock = $this->getAvailableStock($variantCombinationId);
        
        if ($availableStock < $quantity) {
            return [
                'can_add' => false,
                'message' => $availableStock > 0 
                    ? "Only {$availableStock} items available in stock."
                    : 'This item is out of stock.',
                'available_stock' => $availableStock
            ];
        }

        return [
            'can_add' => true,
            'message' => 'Product can be added to cart.',
            'available_stock' => $availableStock
        ];
    }

    // =====================================================
    // ADDITIONAL SCOPES FOR CART (ADD TO EXISTING MODEL)
    // =====================================================

    /**
     * Scope for new arrivals (products created in last 30 days)
     */
    public function scopeNewArrivals($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Scope for best sellers (based on order count)
     */
    public function scopeBestSellers($query)
    {
        return $query->withCount(['orderItems'])
                     ->orderBy('order_items_count', 'desc');
    }

    /**
     * Scope for on sale products
     */
    public function scopeOnSale($query)
{
    return $query->whereHas('variantCombinations', function ($q) {
        $q->whereNotNull('discount_price')
          ->whereRaw('discount_price < combination_price');
    });
}


    /**
 * Get starting price (lowest variant combination price)
 */
public function getStartingPriceAttribute()
{
    return $this->variantCombinations()
        ->where('is_active', true)
        ->min('combination_price') ?? 0;
}

/**
 * Get starting discounted price if any
 */
public function getStartingDiscountPriceAttribute()
{
    $lowestPriceCombination = $this->variantCombinations()
        ->where('is_active', true)
        ->orderBy('combination_price')
        ->first();
    
    return $lowestPriceCombination?->discount_price;
}

/**
 * Check if any variant is on sale
 */
public function getHasDiscountAttribute()
{
    return $this->variantCombinations()
        ->whereNotNull('discount_price')
        ->whereRaw('discount_price < combination_price')
        ->exists();
}

/**
 * Always return true since all products have variants
 */
public function getHasVariantsAttribute()
{
    return true;
}

    
}