<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        $sizeInputs = array_map(
            fn (string $size) => TextInput::make($size)
                ->label($size)
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
                    ->gt('price')
                    ->prefix('Rp')
                    ->helperText('Kosongkan jika produk tidak sedang promo.'),
                TextInput::make('stock_label')
                    ->label('Label stok')
                    ->required()
                    ->default('Tersedia'),

                Repeater::make('stock_table')
                    ->label('Warna, foto, dan stok')
                    ->helperText('Buat satu kartu untuk setiap warna. Angka 0 berarti habis; kosongkan ukuran yang tidak dijual.')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama warna')
                                    ->placeholder('Contoh: Merah marun')
                                    ->required()
                                    ->maxLength(80)
                                    ->distinct(),
                                ColorPicker::make('hex')
                                    ->label('Kode warna')
                                    ->default(StockTable::DEFAULT_HEX)
                                    ->required(),
                                FileUpload::make('image')
                                    ->label('Foto warna')
                                    ->helperText('JPG, PNG, atau WebP. Maksimal 5 MB.')
                                    ->disk('public')
                                    ->directory('products/colors')
                                    ->image()
                                    ->imageEditor()
                                    ->imagePreviewHeight('120')
                                    ->panelLayout('compact')
                                    ->maxSize(5120),
                            ])
                            ->columnSpanFull(),
                        Fieldset::make('Stok per ukuran')
                            ->schema($sizeInputs)
                            ->columns([
                                'default' => 2,
                                'sm' => 3,
                                'lg' => 6,
                            ])
                            ->columnSpanFull(),
                    ])
                    ->itemLabel(fn (array $state): string => filled($state['name'] ?? null)
                        ? 'Warna: '.$state['name']
                        : 'Warna baru')
                    ->default([StockTable::defaultRow()])
                    ->minItems(1)
                    ->addActionLabel('Tambah warna')
                    ->collapsible()
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
                    ->label('Dipublikasikan')
                    ->helperText('Nonaktifkan untuk menyimpan sebagai draf. Produk yang dipublikasikan wajib memiliki foto pada setiap warna.')
                    ->default(false),
                Toggle::make('is_preorder')
                    ->label('Aktifkan informasi Pre-order')
                    ->helperText('Hanya berlaku pada varian dengan stok 0 dan tidak membuat varian dapat dibeli.')
                    ->default(false),
                TextInput::make('sort_order')
                    ->label('Urutan tampil')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}
