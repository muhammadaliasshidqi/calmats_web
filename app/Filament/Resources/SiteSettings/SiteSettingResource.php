<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\CreateSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\ListSiteSettings;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $modelLabel = 'pengaturan situs';

    protected static ?string $pluralModelLabel = 'pengaturan situs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('whatsapp_number')
                ->label('Nomor WhatsApp utama')
                ->placeholder('+6281234567890')
                ->helperText('Gunakan format internasional: tanda + diikuti 8–15 digit, tanpa spasi atau tanda hubung.')
                ->required()
                ->regex('/^\+[1-9]\d{7,14}$/')
                ->validationMessages([
                    'regex' => 'Nomor WhatsApp harus memakai format internasional, contoh +6281234567890.',
                ])
                ->maxLength(16),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('whatsapp_number')
                    ->label('Nomor WhatsApp'),
                TextColumn::make('updated_at')
                    ->label('Terakhir diubah')
                    ->dateTime('d M Y, H:i'),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah nomor'),
            ]);
    }

    public static function canCreate(): bool
    {
        return ! SiteSetting::query()->exists();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteSettings::route('/'),
            'create' => CreateSiteSetting::route('/create'),
            'edit' => EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
