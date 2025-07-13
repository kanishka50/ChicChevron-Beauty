<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'is_approved',
        
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the product being reviewed.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the review.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the rating stars display.
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        $fullStars = floor($this->rating);
        $emptyStars = 5 - $fullStars;

        for ($i = 0; $i < $fullStars; $i++) {
            $stars .= '★';
        }

        for ($i = 0; $i < $emptyStars; $i++) {
            $stars .= '☆';
        }

        return $stars;
    }

    /**
     * Scope for approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope for reviews by rating.
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}