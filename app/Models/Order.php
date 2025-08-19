<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_email',
        'status',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_token',           // ADD THIS
        'payment_initiated_at',    // ADD THIS
        'webhook_received_at',     // ADD THIS
        'payment_verified',        // ADD THIS
        'verification_attempts',   // ADD THIS
        'shipping_name',
        'shipping_phone',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_district',
        'shipping_postal_code',
        'notes',
        'shipped_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_initiated_at' => 'datetime',    // ADD THIS
        'webhook_received_at' => 'datetime',     // ADD THIS
        'payment_verified' => 'boolean',         // ADD THIS
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order status history.
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the promotion usage for this order.
     */
    public function promotionUsage()
    {
        return $this->hasOne(PromotionUsage::class);
    }

    /**
     * Get the complaints for this order.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Get the reviews for products in this order.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'CHB';
        $date = now()->format('Ymd');
        
        // Get the last order of the day
        $lastOrder = static::whereDate('created_at', today())
                          ->orderBy('id', 'desc')
                          ->first();
        
        if ($lastOrder) {
            // Extract the sequence number from the last order
            $lastSequence = (int) substr($lastOrder->order_number, -4);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $date, $sequence);
    }

    /**
     * Get the full shipping address.
     */
    public function getFullShippingAddressAttribute()
    {
        $address = $this->shipping_address_line_1;
        
        if ($this->shipping_address_line_2) {
            $address .= ', ' . $this->shipping_address_line_2;
        }
        
        $address .= ', ' . $this->shipping_city;
        $address .= ', ' . $this->shipping_district;
        
        if ($this->shipping_postal_code) {
            $address .= ' ' . $this->shipping_postal_code;
        }
        
        return $address;
    }

    /**
     * Check if order can be cancelled.
     */
    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['payment_completed', 'processing']);
    }


    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['payment_completed', 'processing']);
    }


    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return [
            'payment_completed' => 'bg-green-100 text-green-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipping' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    /**
     * Add a status history entry.
     */
    /**
 * Add a status history entry.
 */
public function addStatusHistory($status, $comment = null, $adminId = null, $timestamp = null)
{
    return $this->statusHistory()->create([
        'status' => $status,
        'comment' => $comment,
        'changed_by' => $adminId,
        'created_at' => $timestamp ?: now()
    ]);
}

    /**
     * Update order status.
     */
     public function updateStatus($newStatus, $comment = null, $adminId = null)
    {
        $this->status = $newStatus;

        // Update payment status for COD orders when marked as completed
    // if ($newStatus === 'completed' && $this->payment_method === 'cod') {
    //     $this->payment_status = 'completed';
    // }

        $this->save();
        
        $this->addStatusHistory($newStatus, $comment, $adminId);
        
        // Update timestamps based on status
        if ($newStatus === 'shipping') {
            $this->update(['shipped_at' => now()]);
        } elseif ($newStatus === 'completed') {
            $this->update(['completed_at' => now()]);
        }
    }

    /**
     * Get formatted total
     */
    public function getTotalFormattedAttribute()
    {
        return 'Rs. ' . number_format($this->total_amount, 2);
    }

    /**
     * Scope for orders by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for recent orders.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Generate a unique payment token
     */
    public function generatePaymentToken()
    {
        $token = Str::random(32);
        
        // Ensure uniqueness
        while (self::where('payment_token', $token)->exists()) {
            $token = Str::random(32);
        }
        
        $this->payment_token = $token;
        $this->payment_initiated_at = now();
        $this->save();
        
        return $token;
    }

    /**
     * Check if payment token is valid
     */
    public function isPaymentTokenValid($token = null)
    {
        if ($token && !hash_equals($this->payment_token, $token)) {
            return false;
        }

        if (!$this->payment_token) {
            return false;
        }

        if ($this->payment_verified) {
            return false;
        }

        // Check expiry (30 minutes)
        if ($this->payment_initiated_at && $this->payment_initiated_at->addMinutes(30)->isPast()) {
            return false;
        }

        if ($this->verification_attempts >= 3) {
            return false;
        }

        return true;
    }

    /**
     * Mark payment as verified
     */
    public function markPaymentAsVerified()
    {
        $this->payment_verified = true;
        $this->webhook_received_at = now();
        $this->save();
    }

    /**
     * Check if webhook was received
     */
    public function isWebhookReceived()
    {
        return $this->webhook_received_at !== null && $this->payment_verified;
    }

    /**
     * Increment verification attempts
     */
    public function incrementVerificationAttempts()
    {
        $this->increment('verification_attempts');
    }
}