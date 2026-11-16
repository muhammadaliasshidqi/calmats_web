<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'compare_at_price' => $this->compare_at_price,
            'on_promo' => $this->compare_at_price !== null && $this->compare_at_price > $this->price,
            'stock_label' => $this->stock_label,
            'stock_quantity' => $this->stock_quantity,
            'in_stock' => $this->stock_quantity === null || $this->stock_quantity > 0,
            'sizes' => $this->sizes,
            'colors' => $this->colors,
            'material' => $this->material,
            'description' => $this->description,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'is_featured' => $this->is_featured,
        ];
    }
}
