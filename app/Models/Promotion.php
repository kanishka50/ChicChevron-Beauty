<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_percentage',
        'usage_limit',
        'usage_limit_per_customer',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_customer' => 'integer',
        'usage_count' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the products in this promotion.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    /**
     * Get the promotion usage records.
     */
    public function usage()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    /**
     * Check if promotion has reached usage limit.
     */
    public function getHasReachedLimitAttribute()
    {
        if (is_null($this->usage_limit)) {
            return false;
        }

        return $this->usage_count >= $this->usage_limit;
    }

    /**
     * Check if promotion is currently valid.
     */
    public function getIsValidAttribute()
    {
        return $this->is_active && !$this->has_reached_limit;
    }

    /**
     * Check if a user can use this promotion.
     */
    public function canBeUsedBy($userId)
    {
        if (!$this->is_valid) {
            return false;
        }

        $userUsageCount = $this->usage()->where('user_id', $userId)->count();
        return $userUsageCount < $this->usage_limit_per_customer;
    }

    /**
     * Calculate discount amount for a price.
     */
    public function calculateDiscount($price)
    {
        return round($price * ($this->discount_percentage / 100), 2);
    }

    /**
     * Record usage of the promotion.
     */
    public function recordUsage($userId, $orderId, $discountAmount)
    {
        $this->increment('usage_count');
        
        return $this->usage()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
        ]);
    }

    /**
     * Get remaining uses.
     */
    public function getRemainingUsesAttribute()
    {
        if (is_null($this->usage_limit)) {
            return 'Unlimited';
        }

        return max(0, $this->usage_limit - $this->usage_count);
    }

    /**
     * Scope for active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid promotions.
     */
    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('usage_count < usage_limit');
            });
    }
}