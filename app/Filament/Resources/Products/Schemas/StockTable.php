<?php

namespace App\Filament\Resources\Products\Schemas;

/**
 * Menerjemahkan tabel stok di form admin (baris = warna, kolom = ukuran)
 * dari/ke format database lama (colors, sizes, variant_stock, stock_quantity),
 * sehingga keranjang dan Product::availableStock() tidak perlu diubah.
 */
class StockTable
{
    public const SIZES = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

    public const DEFAULT_HEX = '#191613';

    /** Satu baris warna baru dengan semua ukuran bernilai 0. */
    public static function defaultRow(string $name = 'Hitam'): array
    {
        return ['name' => $name, 'hex' => self::DEFAULT_HEX]
            + array_fill_keys(self::SIZES, 0);
    }

    /** Data produk dari database -> isi tabel di form. */
    public static function toForm(array $data): array
    {
        $colors = collect($data['colors'] ?? []);
        $variants = collect($data['variant_stock'] ?? []);
        $legacySizes = $data['sizes'] ?? [];

        $names = $colors->pluck('name')
            ->merge($variants->pluck('color'))
            ->filter()
            ->unique()
            ->values();

        return $names->map(function (string $name) use ($colors, $variants, $legacySizes): array {
            $row = [
                'name' => $name,
                'hex' => $colors->firstWhere('name', $name)['hex'] ?? self::DEFAULT_HEX,
            ];

            foreach (self::SIZES as $size) {
                $variant = $variants->first(
                    fn ($v) => ($v['color'] ?? null) === $name && ($v['size'] ?? null) === $size
                );

                if ($variant !== null) {
                    $row[$size] = (int) ($variant['stock'] ?? 0);
                } else {
                    // Produk lama tanpa stok per varian: ukuran yang dijual diisi 0, sisanya kosong.
                    $row[$size] = ($variants->isEmpty() && in_array($size, $legacySizes, true)) ? 0 : null;
                }
            }

            return $row;
        })->all();
    }

    /** Isi tabel di form -> kolom database. */
    public static function toDatabase(array $data): array
    {
        $rows = collect($data['stock_table'] ?? [])->values();

        $variants = [];
        $offered = [];

        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));

            foreach (self::SIZES as $size) {
                $value = $row[$size] ?? null;

                // Kosong = ukuran tidak dijual. Angka 0 = dijual tapi habis.
                if ($value === null || $value === '') {
                    continue;
                }

                $variants[] = ['size' => $size, 'color' => $name, 'stock' => (int) $value];
                $offered[$size] = true;
            }
        }

        $data['colors'] = $rows->map(fn ($row): array => [
            'name' => trim((string) ($row['name'] ?? '')),
            'hex' => $row['hex'] ?? self::DEFAULT_HEX,
        ])->all();

        $data['sizes'] = array_values(array_filter(self::SIZES, fn ($s) => isset($offered[$s])));
        $data['variant_stock'] = $variants;
        $data['stock_quantity'] = (int) collect($variants)->sum('stock');

        unset($data['stock_table']);

        return $data;
    }
}
