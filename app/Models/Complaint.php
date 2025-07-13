<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number',
        'user_id',
        'order_id',
        'complaint_type',
        'subject',
        'description',
        'status',
    ];

    /**
     * Get the user who filed the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order related to the complaint.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the complaint responses.
     */
    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the complaint type label.
     */
    public function getComplaintTypeLabelAttribute()
    {
        return [
            'product_not_received' => 'Product Not Received',
            'wrong_product' => 'Wrong Product Delivered',
            'damaged_product' => 'Damaged Product',
            'other' => 'Other Issue',
        ][$this->complaint_type] ?? $this->complaint_type;
    }

    /**
     * Get the status color class.
     */
    public function getStatusColorAttribute()
    {
        return [
            'open' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }
}