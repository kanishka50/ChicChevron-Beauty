<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Skin Care',
            'Hair Care',
            'Baby Care',
        ];

        foreach ($types as $type) {
            ProductType::create([
                'name' => $type,
                'slug' => Str::slug($type),
            ]);
        }
    }
}