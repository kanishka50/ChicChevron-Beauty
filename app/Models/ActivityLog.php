<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_type',
        'user_id',
        'action',
        'description',
        'properties',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'properties' => 'array',
    ];

    /**
     * Get the subject of the activity.
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that performed the activity.
     */
    public function user()
    {
        if ($this->log_type === 'admin') {
            return $this->belongsTo(Admin::class, 'user_id');
        }
        
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for admin activities.
     */
    public function scopeAdminActivities($query)
    {
        return $query->where('log_type', 'admin');
    }

    /**
     * Scope for user activities.
     */
    public function scopeUserActivities($query)
    {
        return $query->where('log_type', 'user');
    }

    /**
     * Log an activity.
     */
    public static function log($type, $action, $description = null, $userId = null, $properties = null)
    {
        return static::create([
            'log_type' => $type,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}