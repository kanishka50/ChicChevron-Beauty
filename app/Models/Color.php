<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code', 'rgb_code', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_colors');
    }
}