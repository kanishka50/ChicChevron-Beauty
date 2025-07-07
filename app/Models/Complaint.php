<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'order_id',
        'complaint_type',
        'subject',
        'description',
        'status',
        'priority',
    ];

    protected $casts = [
        'priority' => 'integer',
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
        return $this->hasMany(ComplaintResponse::class)->orderBy('created_at', 'desc');
    }

    /**
     * Generate a unique ticket number.
     */
    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $random = rand(1000, 9999);
        
        $ticketNumber = "{$prefix}-{$date}-{$random}";
        
        // Ensure uniqueness
        while (self::where('ticket_number', $ticketNumber)->exists()) {
            $random = rand(1000, 9999);
            $ticketNumber = "{$prefix}-{$date}-{$random}";
        }
        
        return $ticketNumber;
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
     * Get the status color.
     */
    public function getStatusColorAttribute()
    {
        return [
            'open' => 'red',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            'closed' => 'gray',
        ][$this->status] ?? 'gray';
    }

    /**
     * Get the priority label.
     */
    public function getPriorityLabelAttribute()
    {
        return $this->priority === 1 ? 'High' : 'Normal';
    }

    /**
     * Get the priority color.
     */
    public function getPriorityColorAttribute()
    {
        return $this->priority === 1 ? 'red' : 'gray';
    }

    /**
     * Check if complaint can be responded to.
     */
    public function getCanRespondAttribute()
    {
        return !in_array($this->status, ['closed']);
    }

    /**
     * Add a response to the complaint.
     */
    public function addResponse($message, $isAdminResponse = false, $adminId = null, $userId = null)
    {
        return $this->responses()->create([
            'message' => $message,
            'is_admin_response' => $isAdminResponse,
            'admin_id' => $adminId,
            'user_id' => $userId,
        ]);
    }

    /**
     * Update complaint status.
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();
        
        return $this;
    }

    /**
     * Scope for complaints by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for open complaints.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope for high priority complaints.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 1);
    }
}