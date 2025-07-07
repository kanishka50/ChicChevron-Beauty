<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Skin Care' => [
                'Cleansers',
                'Moisturizers',
                'Serums',
                'Sunscreens',
                'Toners',
                'Exfoliators',
            ],
            'Hair Care' => [
                'Shampoos',
                'Conditioners',
                'Hair Oils',
                'Hair Serums',
                'Hair Masks',
                'Styling Products',
            ],
            'Baby Care' => [
                'Baby Lotions',
                'Baby Creams',
                'Baby Oils',
                'Baby Powders',
                'Diaper Care',
            ],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::create([
                'name' => $parent,
                'slug' => Str::slug($parent),
                'is_active' => true,
            ]);

            foreach ($children as $child) {
                Category::create([
                    'name' => $child,
                    'slug' => Str::slug($child),
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}