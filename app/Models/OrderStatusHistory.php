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

    // Remove getFromStatusLabelAttribute and getToStatusLabelAttribute
}