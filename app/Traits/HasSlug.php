<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });

        static::updating(function ($model) {
            $sourceField = $model->getSlugSourceField();
            
            // Only regenerate slug if the source field has changed and slug is empty
            if ($model->isDirty($sourceField) && empty($model->slug)) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug.
     */
    public function generateSlug()
    {
        $sourceField = $this->getSlugSourceField();
        $slug = Str::slug($this->{$sourceField});
        $originalSlug = $slug;
        $count = 1;

        // Ensure uniqueness
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the field to generate slug from.
     * Override this in your model if needed.
     */
    public function getSlugSourceField(): string
    {
        return 'name';
    }

    /**
     * Get by slug scope.
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Find by slug.
     */
    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Find by slug or fail.
     */
    public static function findBySlugOrFail($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }
}