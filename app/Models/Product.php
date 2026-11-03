<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'category', 'price', 'stock_label', 'stock_quantity',
        'sizes', 'description', 'material', 'image', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock_quantity' => 'integer',
            'sizes' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
