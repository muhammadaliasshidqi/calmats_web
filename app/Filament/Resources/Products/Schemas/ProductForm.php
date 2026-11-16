<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $sizeColumns = array_map(
            fn (string $size) => TableColumn::make($size)->width('80px')->alignCenter(),
            StockTable::SIZES
        );

        $sizeInputs = array_map(
            fn (string $size) => TextInput::make($size)
                ->numeric()
                ->integer()
                ->minValue(0)
                ->default(0)
                ->placeholder('-'),
            StockTable::SIZES
        );

        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Slug URL')
                    ->prefix('/shop/')
                    ->helperText('Isi hanya bagian akhirnya, contoh: kaos-distro.')
                    ->alphaDash()
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
                    ->label('Harga jual')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp'),
                TextInput::make('compare_at_price')
                    ->label('Harga normal sebelum promo')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->helperText('Kosongkan jika produk tidak sedang promo.'),
                TextInput::make('stock_label')
                    ->label('Label stok')
                    ->required()
                    ->default('Tersedia'),

                Repeater::make('stock_table')
                    ->label('Warna dan stok')
                    ->helperText('Setiap baris adalah satu warna. Isi jumlah stok di tiap ukuran. Isi 0 jika habis, kosongkan jika ukuran itu tidak dijual.')
                    ->table(array_merge([
                        TableColumn::make('Nama warna')->width('180px'),
                        TableColumn::make('Warna')->width('120px'),
                    ], $sizeColumns))
                    ->schema(array_merge([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(80)
                            ->distinct(),
                        ColorPicker::make('hex')
                            ->default(StockTable::DEFAULT_HEX)
                            ->required(),
                    ], $sizeInputs))
                    ->default([StockTable::defaultRow()])
                    ->minItems(1)
                    ->addActionLabel('Tambah warna')
                    ->reorderable(false)
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
