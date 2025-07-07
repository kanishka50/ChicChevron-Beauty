<?php

namespace Database\Seeders;

use App\Models\Texture;
use Illuminate\Database\Seeder;

class TextureSeeder extends Seeder
{
    public function run(): void
    {
        $textures = [
            'Cream',
            'Gel',
            'Lotion',
            'Oil',
            'Serum',
            'Foam',
            'Balm',
            'Mist',
            'Powder',
        ];

        foreach ($textures as $texture) {
            Texture::create([
                'name' => $texture,
                'is_default' => true,
            ]);
        }
    }
}