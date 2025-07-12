<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';

    protected $fillable = [
        'order_id',
        'status',        // Changed from 'from_status' and 'to_status'
        'comment',       // Changed from 'notes'
        'changed_by',
    ];

    // Update methods accordingly
    public function getStatusLabelAttribute()
    {
        return [
            'payment_completed' => 'Payment Completed',
            'processing' => 'Processing',
            'shipping' => 'Shipping',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ][$this->status] ?? $this->status;
    }

     public function changedBy()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }

    /**
     * Get the order that owns the status history.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Remove getFromStatusLabelAttribute and getToStatusLabelAttribute
}