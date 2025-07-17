<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesInventory;
use Illuminate\Support\Facades\Auth;

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
        'has_variants',
        'views_count',
        'average_rating',
        'reviews_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_variants' => 'boolean',
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
     * Get active variants.
     */
    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    /**
     * Get the default variant (for products without multiple variants).
     */
    public function defaultVariant()
    {
        return $this->variants()->first();
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
     * Get all inventory records (for all variants).
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
        return $query->whereHas('variants.inventory', function ($inventoryQuery) {
            $inventoryQuery->whereRaw('(current_stock - reserved_stock) > 0');
        });
    }

    /**
     * Scope to get products that are out of stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereDoesntHave('variants.inventory', function ($inventoryQuery) {
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
        return $query->whereHas('variants', function ($q) {
            $q->whereNotNull('discount_price')
              ->whereRaw('discount_price < price');
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
     * Check if product has multiple variants.
     */
    public function getHasMultipleVariantsAttribute()
    {
        return $this->variants()->count() > 1;
    }

    /**
     * Get effective price from first active variant
     */
    public function getEffectivePriceAttribute()
    {
        $variant = $this->activeVariants()->first();
        return $variant ? $variant->effective_price : 0;
    }

    /**
     * Get starting price (lowest variant price)
     */
    public function getStartingPriceAttribute()
    {
        return $this->activeVariants()
            ->min('price') ?? 0;
    }

    /**
     * Get starting discounted price if any
     */
    public function getStartingDiscountPriceAttribute()
    {
        $lowestPriceVariant = $this->activeVariants()
            ->orderBy('price')
            ->first();
        
        return $lowestPriceVariant?->discount_price;
    }

    /**
     * Check if any variant is on sale
     */
    public function getHasDiscountAttribute()
    {
        return $this->activeVariants()
            ->whereNotNull('discount_price')
            ->whereRaw('discount_price < price')
            ->exists();
    }

    /**
     * Get price range for products with multiple variants
     */
    public function getPriceRangeAttribute()
    {
        if (!$this->has_multiple_variants) {
            return null;
        }
        
        $prices = $this->activeVariants()->pluck('price');
        
        if ($prices->isEmpty()) {
            return null;
        }
        
        $min = $prices->min();
        $max = $prices->max();
        
        if ($min == $max) {
            return 'Rs. ' . number_format($min, 2);
        }
        
        return 'Rs. ' . number_format($min, 2) . ' - Rs. ' . number_format($max, 2);
    }

    /**
     * Get effective price range (considering discounts)
     */
    public function getEffectivePriceRangeAttribute()
    {
        $effectivePrices = $this->activeVariants()
            ->get()
            ->map(function ($variant) {
                return $variant->effective_price;
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
        $range = $this->effective_price_range;
        
        if ($range['min'] == $range['max']) {
            return 'Rs. ' . number_format($range['min'], 2);
        }
        
        return 'Rs. ' . number_format($range['min'], 2) . ' - Rs. ' . number_format($range['max'], 2);
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


    /**
 * Get stock status for display
 */
public function getStockStatusAttribute()
{
    $stockLevel = $this->getStockLevel();
    $totalCapacity = $this->variants->sum(function($variant) {
        return $variant->inventory ? $variant->inventory->low_stock_threshold * 3 : 30;
    });
    
    if ($stockLevel == 0) {
        return 'out-of-stock';
    } elseif ($stockLevel <= 10) {
        return 'critical';
    } elseif ($stockLevel <= 30) {
        return 'low';
    } else {
        return 'good';
    }
}

/**
 * Get stock level percentage for visual indicators
 */
public function getStockLevelPercentageAttribute()
{
    $stockLevel = $this->getStockLevel();
    $totalCapacity = $this->variants->sum(function($variant) {
        return $variant->inventory ? $variant->inventory->low_stock_threshold * 3 : 30;
    });
    
    if ($totalCapacity == 0) return 0;
    
    return min(100, round(($stockLevel / $totalCapacity) * 100));
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
     * Get available variants.
     */
    public function getAvailableVariants()
    {
        return $this->activeVariants()
            ->with('inventory')
            ->whereHas('inventory', function ($query) {
                $query->whereRaw('(current_stock - reserved_stock) > 0');
            })
            ->get();
    }

    /**
     * Get variant options grouped by attribute.
     */
    public function getVariantOptions()
    {
        $variants = $this->activeVariants()->get();
        
        return [
            'sizes' => $variants->pluck('size')->filter()->unique()->values(),
            'colors' => $variants->pluck('color')->filter()->unique()->values(),
            'scents' => $variants->pluck('scent')->filter()->unique()->values(),
        ];
    }

    /**
     * Find variant by attributes.
     */
    public function findVariantByAttributes($size = null, $color = null, $scent = null)
    {
        return $this->variants()
            ->where(function ($query) use ($size, $color, $scent) {
                if ($size !== null) {
                    $query->where('size', $size);
                } else {
                    $query->whereNull('size');
                }
                
                if ($color !== null) {
                    $query->where('color', $color);
                } else {
                    $query->whereNull('color');
                }
                
                if ($scent !== null) {
                    $query->where('scent', $scent);
                } else {
                    $query->whereNull('scent');
                }
            })
            ->first();
    }

    /**
     * Get total stock level across all variants
     */
    public function getStockLevel()
    {
        return $this->variants()
            ->with('inventory')
            ->get()
            ->sum(function ($variant) {
                return $variant->inventory 
                    ? ($variant->inventory->current_stock - $variant->inventory->reserved_stock)
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
     * Get available stock for specific variant
     */
    public function getAvailableStock($productVariantId = null)
    {
        if ($productVariantId) {
            $inventory = $this->allInventory()
                             ->where('product_variant_id', $productVariantId)
                             ->first();
        } else {
            // Get total stock across all variants
            return $this->getStockLevel();
        }

        if (!$inventory) {
            return 0;
        }

        return max(0, $inventory->current_stock - $inventory->reserved_stock);
    }

    /**
     * Check if product can be added to cart
     */
    public function canBeAddedToCart($productVariantId = null, $quantity = 1)
    {
        // Check if product is active
        if (!$this->is_active) {
            return [
                'can_add' => false,
                'message' => 'This product is no longer available.'
            ];
        }

        // Always require variant selection
        if (!$productVariantId) {
            return [
                'can_add' => false,
                'message' => 'Please select product options.'
            ];
        }

        // Check if variant exists and is active
        $variant = $this->variants()->find($productVariantId);
        if (!$variant || !$variant->is_active) {
            return [
                'can_add' => false,
                'message' => 'Selected variant is not available.'
            ];
        }

        // Check if variant has valid price
if ($variant->price <= 0) {
    return [
        'can_add' => false,
        'message' => 'Price not set for this variant.'
    ];
}

        // Check stock availability
        $availableStock = $this->getAvailableStock($productVariantId);
        
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
    // STATIC METHODS
    // =====================================================

    /**
     * Get featured products for homepage.
     */
    public static function getFeaturedProducts($limit = 8)
    {
        return static::active()
            ->with(['brand', 'category', 'images', 'variants.inventory'])
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
            ->with(['brand', 'category', 'images', 'variants.inventory'])
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
            ->with(['brand', 'category', 'images', 'variants.inventory'])
            ->inStock()
            ->limit($limit)
            ->get();
    }

/**
 * Check if product is in user's wishlist
 */
public function isInWishlist()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return $this->wishlists()
            ->where('user_id', Auth::id())
            ->exists();
    }
    
}