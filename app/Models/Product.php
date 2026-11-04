<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'category', 'price', 'compare_at_price', 'stock_label', 'stock_quantity',
        'sizes', 'colors', 'variant_stock', 'description', 'material', 'image', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'compare_at_price' => 'integer',
            'stock_quantity' => 'integer',
            'sizes' => 'array',
            'colors' => 'array',
            'variant_stock' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function availableStock(string $size, string $color): ?int
    {
        $variants = collect($this->variant_stock ?? []);

        if ($variants->isNotEmpty()) {
            $variant = $variants->first(fn (array $item): bool =>
                ($item['size'] ?? null) === $size && ($item['color'] ?? null) === $color
            );

            return (int) ($variant['stock'] ?? 0);
        }

        return $this->stock_quantity;
    }
}
