<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'category', 'price', 'compare_at_price', 'stock_label', 'stock_quantity',
        'sizes', 'colors', 'variant_stock', 'description', 'material', 'image', 'is_featured', 'is_active',
        'is_preorder', 'published_at', 'sort_order',
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
            'is_preorder' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if ($product->is_active && ! $product->published_at) {
                $product->published_at = now();
            }

            if (! $product->is_active) {
                $product->published_at = null;
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)->whereNotNull('published_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function availableStock(string $size, string $color): ?int
    {
        $variants = collect($this->variant_stock ?? []);

        if ($variants->isNotEmpty()) {
            $variant = $variants->first(fn (array $item): bool => ($item['size'] ?? null) === $size && ($item['color'] ?? null) === $color
            );

            return (int) ($variant['stock'] ?? 0);
        }

        return $this->stock_quantity;
    }

    public function hasReadyStock(): bool
    {
        $variants = collect($this->variant_stock ?? []);

        return $variants->isNotEmpty()
            ? $variants->contains(fn (array $variant): bool => (int) ($variant['stock'] ?? 0) > 0)
            : ($this->stock_quantity === null || $this->stock_quantity > 0);
    }

    public function hasPreorderVariants(): bool
    {
        if (! $this->is_preorder) {
            return false;
        }

        $variants = collect($this->variant_stock ?? []);

        return $variants->isEmpty()
            ? ! $this->hasReadyStock()
            : $variants->contains(fn (array $variant): bool => (int) ($variant['stock'] ?? 0) <= 0);
    }

    public function isSoldOut(): bool
    {
        return ! $this->hasReadyStock() && ! $this->hasPreorderVariants();
    }

    public function matchesAvailability(string $availability): bool
    {
        return match ($availability) {
            'ready' => $this->hasReadyStock(),
            'preorder' => $this->hasPreorderVariants(),
            'sold_out' => $this->isSoldOut(),
            default => true,
        };
    }

    /** @return array<string, string> */
    public static function publicationErrors(array $data): array
    {
        if (! ($data['is_active'] ?? false)) {
            return [];
        }

        $errors = [];
        $colors = collect($data['colors'] ?? []);

        if ($colors->isEmpty()) {
            $errors['stock_table'] = 'Produk yang dipublikasikan harus memiliki minimal satu warna.';
        } elseif ($colors->contains(fn (array $color): bool => blank($color['image'] ?? null))) {
            $errors['stock_table'] = 'Setiap warna wajib memiliki foto sebelum produk dipublikasikan.';
        }

        if (empty($data['variant_stock'])) {
            $errors['stock_table'] = 'Produk yang dipublikasikan harus memiliki minimal satu kombinasi warna dan ukuran.';
        }

        return $errors;
    }
}
