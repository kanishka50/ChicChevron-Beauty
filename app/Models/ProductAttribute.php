<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_name',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    /**
     * Available attribute types
     */
    public const ATTRIBUTE_TYPES = [
        'size' => 'Size',
        'color' => 'Color',
        'scent' => 'Scent',
        'shade' => 'Shade',
        'finish' => 'Finish',
        'type' => 'Type',
    ];

    /**
     * Get the product that owns this attribute.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the options for this attribute.
     */
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class)->orderBy('display_order');
    }

    /**
     * Get formatted attribute name for display.
     */
    public function getDisplayNameAttribute(): string
    {
        return self::ATTRIBUTE_TYPES[$this->attribute_name] ?? ucfirst($this->attribute_name);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
