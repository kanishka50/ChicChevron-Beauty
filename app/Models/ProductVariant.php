<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesInventory;

class ProductVariant extends Model
{
    use HasFactory, ManagesInventory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'discount_price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all attribute values for this variant.
     */
    public function attributeValues()
    {
        return $this->hasMany(VariantAttributeValue::class);
    }

    /**
     * Get all attribute options for this variant (many-to-many through pivot).
     */
    public function attributeOptions()
    {
        return $this->belongsToMany(AttributeOption::class, 'variant_attribute_values');
    }

    /**
     * Get a specific attribute option value by attribute name.
     */
    public function getOptionValue(string $attributeName): ?string
    {
        return $this->attributeOptions()
            ->whereHas('productAttribute', fn($q) => $q->where('attribute_name', $attributeName))
            ->first()?->value;
    }

    // Accessors
    public function getEffectivePriceAttribute()
    {
        if ($this->discount_price && $this->discount_price < $this->price) {
            return $this->discount_price;
        }
        return $this->price;
    }

    public function getAvailableStockAttribute()
    {
        $inventory = $this->inventory;
        if (!$inventory) return 0;

        return max(0, $inventory->stock_quantity - $inventory->reserved_quantity);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    /**
 * Get display name for variant (formatted attributes)
 */
public function getDisplayNameAttribute()
{
    if ($this->name) {
        return $this->name;
    }

    // Build from attribute options
    $options = $this->attributeOptions()->with('productAttribute')->get();
    if ($options->isEmpty()) {
        return 'Standard';
    }

    return $options->pluck('value')->implode(' - ');
}

/**
 * Get stock level percentage for visual indicators
 */
public function getStockLevelPercentageAttribute()
{
    if (!$this->inventory) return 0;
    
    $availableStock = $this->available_stock;
    $threshold = $this->inventory->low_stock_threshold;
    $referenceStock = $threshold * 3; // Consider "full" as 3x threshold
    
    if ($referenceStock == 0) return 0;
    
    return min(100, round(($availableStock / $referenceStock) * 100));
}

/**
 * Get stock status class for styling
 */
public function getStockStatusClassAttribute()
{
    if (!$this->inventory) return 'bg-gray-200';
    
    $percentage = $this->stock_level_percentage;
    
    if ($percentage == 0) {
        return 'bg-red-500';
    } elseif ($percentage <= 20) {
        return 'bg-orange-500';
    } elseif ($percentage <= 50) {
        return 'bg-yellow-500';
    } else {
        return 'bg-green-500';
    }
}

/**
 * Check if variant is in stock
 */
public function getInStockAttribute()
{
    return $this->available_stock > 0;
}

/**
 * Scope to get variants in stock
 */
public function scopeInStock($query)
{
    return $query->whereHas('inventory', function ($q) {
        $q->whereRaw('(stock_quantity - reserved_quantity) > 0');
    });
}

/**
 * Get the hex code for this variant's color
 * Uses a predefined color map for common beauty product colors
 *
 * @return string|null The hex code (e.g., "#FF0000") or null if not found
 */
public function getColorHexCodeAttribute()
{
    $colorValue = $this->getAttributeValue('color');
    if (!$colorValue) {
        return null;
    }

    // Common beauty product colors with hex codes
    $colorMap = [
        'red' => '#DC2626',
        'pink' => '#EC4899',
        'rose' => '#F43F5E',
        'coral' => '#FB7185',
        'peach' => '#FDBA74',
        'nude' => '#D4A574',
        'beige' => '#D2B48C',
        'brown' => '#92400E',
        'orange' => '#EA580C',
        'gold' => '#D97706',
        'yellow' => '#EAB308',
        'green' => '#16A34A',
        'teal' => '#14B8A6',
        'blue' => '#2563EB',
        'purple' => '#9333EA',
        'violet' => '#7C3AED',
        'plum' => '#A21CAF',
        'burgundy' => '#881337',
        'wine' => '#7F1D1D',
        'black' => '#1F2937',
        'white' => '#F9FAFB',
        'silver' => '#9CA3AF',
        'grey' => '#6B7280',
        'gray' => '#6B7280',
        'cream' => '#FFFDD0',
        'ivory' => '#FFFFF0',
        'natural' => '#E5D3B3',
        'clear' => '#E5E7EB',
        'transparent' => '#E5E7EB',
    ];

    $colorLower = strtolower(trim($colorValue));

    return $colorMap[$colorLower] ?? '#9CA3AF';
}

/**
 * Get formatted variant attributes for display
 *
 * @return array Array of attribute name => value pairs
 */
public function getFormattedAttributesAttribute()
{
    $options = $this->attributeOptions()->with('productAttribute')->get();

    return $options->mapWithKeys(function ($option) {
        $attrName = $option->productAttribute?->attribute_name ?? 'Unknown';
        return [ucfirst($attrName) => $option->value];
    })->toArray();
}

/**
 * Check if variant has a discount applied
 */
public function getHasDiscountAttribute()
{
    return $this->discount_price && $this->discount_price < $this->price;
}

/**
 * Get discount percentage
 */
public function getDiscountPercentageAttribute()
{
    if (!$this->has_discount || $this->price <= 0) {
        return 0;
    }

    return round((($this->price - $this->discount_price) / $this->price) * 100);
}
}