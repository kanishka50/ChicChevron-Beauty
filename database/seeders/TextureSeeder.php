<?php

namespace Database\Seeders;

use App\Models\Texture;
use Illuminate\Database\Seeder;

class TextureSeeder extends Seeder
{
    public function run(): void
    {
        $textures = [
            ['name' => 'Cream', 'is_default' => true],
            ['name' => 'Gel', 'is_default' => true],
            ['name' => 'Lotion', 'is_default' => true],
            ['name' => 'Oil', 'is_default' => true],
            ['name' => 'Serum', 'is_default' => true],
            ['name' => 'Foam', 'is_default' => true],
            ['name' => 'Balm', 'is_default' => true],
            ['name' => 'Powder', 'is_default' => true],
            ['name' => 'Stick', 'is_default' => true],
            ['name' => 'Spray', 'is_default' => true],
            ['name' => 'Mousse', 'is_default' => true],
            ['name' => 'Wax', 'is_default' => true],
            ['name' => 'Liquid', 'is_default' => true],
            ['name' => 'Paste', 'is_default' => true],
            ['name' => 'Butter', 'is_default' => true],
            ['name' => 'Mist', 'is_default' => true],
        ];

        foreach ($textures as $texture) {
            Texture::create($texture);
        }

        $this->command->info('✅ Textures seeded successfully! Created ' . count($textures) . ' textures.');
    }
}