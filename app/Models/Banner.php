<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_desktop',
        'image_mobile',
        'link_type',
        'link_value',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Scope for active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered banners
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get desktop image URL
     */
    public function getDesktopImageUrlAttribute()
    {
        return asset('storage/' . $this->image_desktop);
    }

    /**
     * Get mobile image URL
     */
    public function getMobileImageUrlAttribute()
    {
        return $this->image_mobile ? asset('storage/' . $this->image_mobile) : null;
    }

    /**
     * Get the full URL based on link type
     */
    public function getFullUrlAttribute()
    {
        switch ($this->link_type) {
            case 'product':
                $product = \App\Models\Product::where('slug', $this->link_value)->first();
                return $product ? route('products.show', $product->slug) : '#';
            case 'category':
                return route('products.index', ['category' => $this->link_value]);
            case 'url':
                return $this->link_value;
            default:
                return '#';
        }
    }

    /**
     * Backward compatibility for old attribute names
     */
    public function getImageUrlAttribute()
    {
        return $this->desktop_image_url;
    }

    public function getImagePathAttribute()
    {
        return $this->image_desktop;
    }

    public function getLinkUrlAttribute()
    {
        return $this->full_url;
    }

    public function getLinkTextAttribute()
    {
        return $this->link_type !== 'none' ? 'Shop Now' : null;
    }
}