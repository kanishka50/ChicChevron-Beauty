<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Clear', 'hex_code' => '#FFFFFF', 'is_default' => true],
            ['name' => 'White', 'hex_code' => '#FFFFFF', 'is_default' => true],
            ['name' => 'Ivory', 'hex_code' => '#FFFFF0', 'is_default' => true],
            ['name' => 'Beige', 'hex_code' => '#F5F5DC', 'is_default' => true],
            ['name' => 'Light Pink', 'hex_code' => '#FFB6C1', 'is_default' => true],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB', 'is_default' => true],
            ['name' => 'Rose', 'hex_code' => '#FF69B4', 'is_default' => true],
            ['name' => 'Coral', 'hex_code' => '#FF7F50', 'is_default' => true],
            ['name' => 'Peach', 'hex_code' => '#FFCBA4', 'is_default' => true],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'is_default' => true],
            ['name' => 'Red', 'hex_code' => '#FF0000', 'is_default' => true],
            ['name' => 'Burgundy', 'hex_code' => '#800020', 'is_default' => true],
            ['name' => 'Purple', 'hex_code' => '#800080', 'is_default' => true],
            ['name' => 'Lavender', 'hex_code' => '#E6E6FA', 'is_default' => true],
            ['name' => 'Blue', 'hex_code' => '#0000FF', 'is_default' => true],
            ['name' => 'Navy', 'hex_code' => '#000080', 'is_default' => true],
            ['name' => 'Teal', 'hex_code' => '#008080', 'is_default' => true],
            ['name' => 'Green', 'hex_code' => '#008000', 'is_default' => true],
            ['name' => 'Mint', 'hex_code' => '#98FB98', 'is_default' => true],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00', 'is_default' => true],
            ['name' => 'Gold', 'hex_code' => '#FFD700', 'is_default' => true],
            ['name' => 'Bronze', 'hex_code' => '#CD7F32', 'is_default' => true],
            ['name' => 'Brown', 'hex_code' => '#A52A2A', 'is_default' => true],
            ['name' => 'Nude', 'hex_code' => '#F3E5D0', 'is_default' => true],
            ['name' => 'Tan', 'hex_code' => '#D2B48C', 'is_default' => true],
            ['name' => 'Black', 'hex_code' => '#000000', 'is_default' => true],
            ['name' => 'Gray', 'hex_code' => '#808080', 'is_default' => true],
            ['name' => 'Silver', 'hex_code' => '#C0C0C0', 'is_default' => true],
            ['name' => 'Rose Gold', 'hex_code' => '#B76E79', 'is_default' => true],
            ['name' => 'Maroon', 'hex_code' => '#800000', 'is_default' => true],
            ['name' => 'Transparent', 'hex_code' => '#FFFFFF', 'is_default' => true],
        ];

        foreach ($colors as $color) {
            Color::create($color);
        }

        $this->command->info('✅ Colors seeded successfully! Created ' . count($colors) . ' colors.');
    }
}