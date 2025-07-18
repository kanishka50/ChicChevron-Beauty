<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the order status changes made by this admin.
     */
    public function orderStatusChanges()
    {
        return $this->hasMany(OrderStatusHistory::class, 'changed_by');
    }

    /**
     * Get the complaint responses made by this admin.
     */
    public function complaintResponses()
    {
        return $this->hasMany(ComplaintResponse::class);
    }
}