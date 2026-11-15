<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $allSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        $colors = [
            ['name' => 'Hitam', 'hex' => '#191613'],
            ['name' => 'Putih', 'hex' => '#FFFFFF'],
            ['name' => 'Navy', 'hex' => '#1F2A44'],
        ];

        // [nama, kategori, harga, harga normal, material, unggulan, ukuran yang dijual]
        $items = [
            ['Kaos Basic Oversize', 'Tops', 129000, 159000, 'Cotton Combed 24s', true, $allSizes],
            ['Kemeja Linen Lengan Panjang', 'Tops', 249000, null, 'Linen', true, $allSizes],
            ['Hoodie Fleece Classic', 'Outerwear', 289000, 349000, 'Fleece', true, $allSizes],
            ['Celana Chino Slim', 'Bottoms', 229000, null, 'Twill Stretch', false, ['S', 'M', 'L', 'XL']],
            ['Jaket Coach Water Resistant', 'Outerwear', 319000, null, 'Parachute', false, $allSizes],
            ['Topi Dad Hat', 'Accessories', 89000, null, 'Cotton Twill', false, ['M']],
        ];

        foreach ($items as $i => [$name, $category, $price, $compare, $material, $featured, $sizes]) {
            $variants = [];

            foreach ($colors as $color) {
                foreach ($sizes as $size) {
                    $variants[] = ['size' => $size, 'color' => $color['name'], 'stock' => random_int(0, 20)];
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
