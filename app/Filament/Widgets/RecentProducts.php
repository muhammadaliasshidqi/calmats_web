<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentProducts extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->latest()->limit(5))
            ->heading('Produk terbaru')
            ->description('Lima produk yang terakhir ditambahkan ke katalog.')
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id'),
                TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->numeric(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Tambah produk')
                    ->icon('heroicon-o-plus')
                    ->url(ProductResource::getUrl('create')),
            ])
            ->recordUrl(fn (Product $record): string => ProductResource::getUrl('edit', ['record' => $record]))
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-sparkles')
            ->emptyStateHeading('Katalog masih kosong')
            ->emptyStateDescription('Tambahkan produk pertama untuk mulai mengisi storefront CALMATS.')
            ->emptyStateActions([
                Action::make('createFirstProduct')
                    ->label('Tambah produk pertama')
                    ->icon('heroicon-o-plus')
                    ->url(ProductResource::getUrl('create')),
            ]);
    }
}
