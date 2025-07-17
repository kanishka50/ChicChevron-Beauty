<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Texture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $costPrice = $this->faker->randomFloat(2, 100, 1000);
        $sellingPrice = $costPrice * $this->faker->randomFloat(2, 1.5, 3);
        
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'sku' => strtoupper($this->faker->bothify('??##??##')),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'texture_id' => Texture::factory(),
            'cost_price' => $costPrice,
            'selling_price' => $sellingPrice,
            'discount_price' => $this->faker->boolean(30) ? $sellingPrice * 0.8 : null,
            'main_image' => 'products/placeholder.jpg',
            'how_to_use' => $this->faker->paragraphs(2, true),
            'suitable_for' => $this->faker->randomElement(['All skin types', 'Dry skin', 'Oily skin', 'Combination skin']),
            'fragrance' => $this->faker->randomElement(['Rose', 'Lavender', 'Unscented', 'Citrus']),
            'has_variants' => $this->faker->boolean(40),
            'is_active' => true,
            'views_count' => 0,
        ];
    }
}