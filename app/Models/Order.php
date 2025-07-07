<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_reference',
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
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        
        $orderNumber = "{$prefix}-{$date}-{$random}";
        
        // Ensure uniqueness
        while (self::where('order_number', $orderNumber)->exists()) {
            $random = strtoupper(substr(uniqid(), -4));
            $orderNumber = "{$prefix}-{$date}-{$random}";
        }
        
        return $orderNumber;
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
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return [
            'payment_completed' => 'blue',
            'processing' => 'yellow',
            'shipping' => 'indigo',
            'completed' => 'green',
            'cancelled' => 'red',
        ][$this->status] ?? 'gray';
    }

    /**
     * Add a status history entry.
     */
    public function addStatusHistory($status, $comment = null, $adminId = null)
    {
        return $this->statusHistory()->create([
            'status' => $status,
            'comment' => $comment,
            'changed_by' => $adminId,
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus($status, $comment = null, $adminId = null)
    {
        $this->status = $status;
        
        if ($status === 'shipping') {
            $this->shipped_at = now();
        } elseif ($status === 'completed') {
            $this->completed_at = now();
        }
        
        $this->save();
        $this->addStatusHistory($status, $comment, $adminId);
        
        return $this;
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
}