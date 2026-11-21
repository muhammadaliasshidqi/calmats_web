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
            'in_stock' => $this->hasReadyStock(),
            'is_preorder' => $this->is_preorder,
            'availability' => $this->isSoldOut()
                ? 'sold_out'
                : ($this->hasPreorderVariants() ? 'preorder' : 'ready'),
            'sizes' => $this->sizes,
            'colors' => collect($this->colors ?? [])->map(fn (array $color): array => [
                ...$color,
                'image' => filled($color['image'] ?? null)
                    ? Storage::disk('public')->url($color['image'])
                    : null,
            ])->values(),
            'material' => $this->material,
            'description' => $this->description,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at,
        ];
    }
}
