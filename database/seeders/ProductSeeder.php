<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Kaos Basic Oversize', 'Kaos', 129000, 159000, 'Cotton Combed 24s', true],
            ['Kemeja Linen Lengan Panjang', 'Kemeja', 249000, null, 'Linen', true],
            ['Hoodie Fleece Classic', 'Hoodie', 289000, 349000, 'Fleece', true],
            ['Celana Chino Slim', 'Celana', 229000, null, 'Twill Stretch', false],
            ['Jaket Coach Water Resistant', 'Jaket', 319000, null, 'Parachute', false],
            ['Topi Dad Hat', 'Aksesoris', 89000, null, 'Cotton Twill', false],
        ];

        foreach ($items as $i => [$name, $category, $price, $compare, $material, $featured]) {
            $sizes = $category === 'Aksesoris' ? ['One size'] : ['S', 'M', 'L', 'XL'];
            $colors = ['Hitam', 'Putih', 'Navy'];

            $variants = [];
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    $variants[] = ['size' => $size, 'color' => $color, 'stock' => random_int(0, 20)];
                }
            }

            Product::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'category' => $category,
                    'price' => $price,
                    'compare_at_price' => $compare,
                    'stock_label' => 'Tersedia',
                    'stock_quantity' => collect($variants)->sum('stock'),
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'variant_stock' => $variants,
                    'description' => "{$name} dengan bahan {$material}, nyaman dipakai sehari-hari.",
                    'material' => $material,
                    'image' => null,
                    'is_featured' => $featured,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]
            );
        }
    }
}