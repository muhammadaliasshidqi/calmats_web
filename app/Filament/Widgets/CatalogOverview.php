<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CatalogOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Ringkasan katalog';

    protected ?string $description = 'Status produk yang tersimpan saat ini.';

    protected function getStats(): array
    {
        return [
            Stat::make('Total produk', Product::query()->count())
                ->description('Semua data katalog')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Produk aktif', Product::query()->where('is_active', true)->count())
                ->description('Tampil di storefront')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Stok habis', Product::query()->where('stock_quantity', '<=', 0)->count())
                ->description('Perlu diperiksa')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->url(ProductResource::getUrl('index')),
            Stat::make('Produk unggulan', Product::query()->where('is_featured', true)->count())
                ->description('Ditampilkan di Home')
                ->icon('heroicon-o-star')
                ->color('warning')
                ->url(ProductResource::getUrl('index')),
        ];
    }
}
