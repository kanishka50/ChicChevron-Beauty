<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'notes',
        'changed_by',
    ];

    /**
     * Get the order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the admin who changed the status.
     */
    public function changedBy()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }

    /**
     * Get from status label.
     */
    public function getFromStatusLabelAttribute()
    {
        return [
            'payment_completed' => 'Payment Completed',
            'processing' => 'Processing',
            'shipping' => 'Shipping',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ][$this->from_status] ?? $this->from_status;
    }

    /**
     * Get to status label.
     */
    public function getToStatusLabelAttribute()
    {
        return [
            'payment_completed' => 'Payment Completed',
            'processing' => 'Processing',
            'shipping' => 'Shipping',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ][$this->to_status] ?? $this->to_status;
    }
}