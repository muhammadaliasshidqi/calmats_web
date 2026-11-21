<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Schemas\StockTable;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = StockTable::toDatabase($data);
        $errors = Product::publicationErrors($data);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $data;
    }
}
