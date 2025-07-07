<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug'];

    public function getSlugSourceField(): string
    {
        return 'name';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}