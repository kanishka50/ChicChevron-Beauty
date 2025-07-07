<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Log on create
        static::created(function ($model) {
            $model->logActivity('created', 'Created ' . class_basename($model));
        });

        // Log on update
        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                $model->logActivity('updated', 'Updated ' . class_basename($model), $changes);
            }
        });

        // Log on delete
        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Deleted ' . class_basename($model));
        });
    }

    /**
     * Log an activity
     *
     * @param string $action
     * @param string $description
     * @param array|null $properties
     * @return void
     */
    public function logActivity($action, $description = null, $properties = null)
    {
        $logType = $this->getLogType();
        $userId = $this->getActivityUserId();

        ActivityLog::create([
            'log_type' => $logType,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description ?? $action . ' ' . class_basename($this),
            'properties' => $properties ? json_encode($properties) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get the log type based on the current guard
     *
     * @return string
     */
    protected function getLogType()
    {
        if (auth()->guard('admin')->check()) {
            return 'admin';
        } elseif (auth()->guard('web')->check()) {
            return 'user';
        }
        
        return 'system';
    }

    /**
     * Get the user ID for the activity log
     *
     * @return int|null
     */
    protected function getActivityUserId()
    {
        if (auth()->guard('admin')->check()) {
            return auth()->guard('admin')->id();
        } elseif (auth()->guard('web')->check()) {
            return auth()->guard('web')->id();
        }
        
        return null;
    }

    /**
     * Get activities for this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}