<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'user_id',
        'email_type',
        'recipient_email',
        'subject',
        'status',
        'error_message',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logEmail($type, $recipient, $subject, $status = 'sent', $error = null, $userId = null)
    {
        return static::create([
            'user_id' => $userId,
            'email_type' => $type,
            'recipient_email' => $recipient,
            'subject' => $subject,
            'status' => $status,
            'error_message' => $error,
        ]);
    }
}