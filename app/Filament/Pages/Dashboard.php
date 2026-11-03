<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminWelcome;
use App\Filament\Widgets\CatalogOverview;
use App\Filament\Widgets\RecentProducts;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Components\Attributes\Unexposed;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    #[Unexposed]
    public function getTitle(): string | Htmlable
    {
        return 'Control room';
    }

    #[Unexposed]
    public function getSubheading(): string | Htmlable | null
    {
        return 'Kelola katalog CALMATS dari satu tempat.';
    }

    #[Unexposed]
    public function getColumns(): int | array
    {
        return 1;
    }

    #[Unexposed]
    public function getWidgets(): array
    {
        return [
            AdminWelcome::class,
            CatalogOverview::class,
            RecentProducts::class,
        ];
    }
}
