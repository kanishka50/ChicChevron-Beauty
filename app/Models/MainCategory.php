<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name'];

    /**
     * Get the categories for this main category.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}