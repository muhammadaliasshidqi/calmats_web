<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Slug URL')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nama produk')
                    ->required(),
                Select::make('category')
                    ->label('Kategori')
                    ->options(['Tops' => 'Tops', 'Bottoms' => 'Bottoms', 'Outerwear' => 'Outerwear', 'Accessories' => 'Accessories'])
                    ->searchable()
                    ->required(),
                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                TextInput::make('stock_label')
                    ->label('Label stok')
                    ->required()
                    ->default('Tersedia'),
                TextInput::make('stock_quantity')
                    ->label('Jumlah stok')
                    ->minValue(0)
                    ->numeric(),
                TagsInput::make('sizes')
                    ->label('Ukuran')
                    ->placeholder('Tambahkan ukuran')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('material')
                    ->label('Material')
                    ->required(),
                FileUpload::make('image')
                    ->label('Foto produk')
                    ->disk('public')
                    ->directory('products')
                    ->image()
                    ->imageEditor()
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->label('Tampilkan di koleksi unggulan')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Produk aktif')
                    ->default(true),
                TextInput::make('sort_order')
                    ->label('Urutan tampil')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}
