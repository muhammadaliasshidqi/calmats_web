<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Schemas\StockTable;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['stock_table'] = StockTable::toForm($data);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = StockTable::toDatabase($data);
        $errors = Product::publicationErrors($data);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $data;
    }
}
