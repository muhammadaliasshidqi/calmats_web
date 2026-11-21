<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        $v = $request->validated();
        $query = Product::query()->published();

        if (! empty($v['q'])) {
            $term = '%'.addcslashes($v['q'], '%_\\').'%';
            $query->where(fn ($q) => $q
                ->where('name', 'like', $term)
                ->orWhere('category', 'like', $term)
                ->orWhere('material', 'like', $term)
                ->orWhere('description', 'like', $term));
        }

        if (! empty($v['category'])) {
            $query->where('category', $v['category']);
        }

        if (isset($v['min_price'])) {
            $query->where('price', '>=', $v['min_price']);
        }

        if (isset($v['max_price'])) {
            $query->where('price', '<=', $v['max_price']);
        }

        if ($request->boolean('in_stock')) {
            // stock_quantity kosong berarti stok tidak dibatasi.
            $query->where(fn ($q) => $q->whereNull('stock_quantity')->orWhere('stock_quantity', '>', 0));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        match ($v['sort'] ?? 'default') {
            'latest' => $query->latest()->orderByDesc('id'),
            'name' => $query->orderBy('name'),
            'price_asc' => $query->orderBy('price')->orderBy('name'),
            'price_desc' => $query->orderByDesc('price')->orderBy('name'),
            default => $query->orderBy('sort_order')->orderBy('name'),
        };

        return ProductResource::collection(
            $query->paginate($v['per_page'] ?? 12)->withQueryString()
        );
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active && $product->published_at, 404);

        return new ProductResource($product);
    }

    public function categories()
    {
        return response()->json([
            'data' => Product::query()
                ->published()
                ->orderBy('category')
                ->distinct()
                ->pluck('category'),
        ]);
    }
}
