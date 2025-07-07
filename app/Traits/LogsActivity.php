<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity($action)
    {
        ActivityLog::create([
            'log_type' => $this->getLogType(),
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => class_basename($this) . ' ' . $action . ': ' . $this->getLogDescription(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    protected function getLogType()
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        } elseif (Auth::check()) {
            return 'user';
        }
        
        return 'system';
    }

    protected function getLogDescription()
    {
        return $this->name ?? $this->id;
    }
}