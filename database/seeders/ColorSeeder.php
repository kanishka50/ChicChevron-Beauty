<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Clear', 'color_code' => '#FFFFFF', 'is_default' => true],
            ['name' => 'White', 'color_code' => '#FFFFFF', 'is_default' => true],
            ['name' => 'Pink', 'color_code' => '#FFC0CB', 'is_default' => true],
            ['name' => 'Peach', 'color_code' => '#FFDAB9', 'is_default' => true],
            ['name' => 'Yellow', 'color_code' => '#FFFF00', 'is_default' => true],
            ['name' => 'Orange', 'color_code' => '#FFA500', 'is_default' => true],
            ['name' => 'Red', 'color_code' => '#FF0000', 'is_default' => true],
            ['name' => 'Purple', 'color_code' => '#800080', 'is_default' => true],
            ['name' => 'Blue', 'color_code' => '#0000FF', 'is_default' => true],
            ['name' => 'Green', 'color_code' => '#008000', 'is_default' => true],
            ['name' => 'Brown', 'color_code' => '#A52A2A', 'is_default' => true],
            ['name' => 'Black', 'color_code' => '#000000', 'is_default' => true],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }
    }
}