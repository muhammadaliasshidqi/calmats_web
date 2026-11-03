<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($name),
            'name' => Str::title($name),
            'category' => fake()->randomElement(['Tops', 'Bottoms', 'Outerwear', 'Accessories']),
            'price' => fake()->numberBetween(149000, 599000),
            'stock_label' => 'Tersedia S–XL',
            'stock_quantity' => fake()->numberBetween(0, 50),
            'sizes' => ['S', 'M', 'L', 'XL'],
            'description' => fake()->sentence(14),
            'material' => 'Cotton',
            'image' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
