<?php

namespace Tests\Unit;

use App\Filament\Resources\Products\Schemas\StockTable;
use PHPUnit\Framework\TestCase;

class StockTableTest extends TestCase
{
    public function test_color_images_are_preserved_between_form_and_database_formats(): void
    {
        $database = StockTable::toDatabase([
            'stock_table' => [[
                'name' => 'Merah',
                'hex' => '#ff0000',
                'image' => 'products/colors/merah.jpg',
                'S' => 3,
                'M' => 0,
                'L' => null,
                'XL' => null,
                'XXL' => null,
                'XXXL' => null,
            ]],
        ]);

        $this->assertSame('products/colors/merah.jpg', $database['colors'][0]['image']);
        $this->assertSame(['S', 'M'], $database['sizes']);

        $form = StockTable::toForm($database);

        $this->assertSame('products/colors/merah.jpg', $form[0]['image']);
        $this->assertSame(3, $form[0]['S']);
        $this->assertSame(0, $form[0]['M']);
    }
}
