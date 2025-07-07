<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'L\'Oreal',
            'Nivea',
            'Dove',
            'Garnier',
            'Neutrogena',
            'Olay',
            'The Body Shop',
            'Cetaphil',
            'Himalaya',
            'Johnson\'s Baby',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'is_active' => true,
            ]);
        }
    }
}