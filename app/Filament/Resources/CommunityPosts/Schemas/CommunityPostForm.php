<?php

namespace App\Filament\Resources\CommunityPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CommunityPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Foto komunitas')
                    ->disk('public')
                    ->directory('community')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
                Select::make('user_id')
                    ->label('Pengirim')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled(),
                Select::make('status')
                    ->label('Status moderasi')
                    ->options([
                        'pending' => 'Menunggu moderasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),
                Textarea::make('caption')
                    ->label('Caption')
                    ->rows(5)
                    ->maxLength(500)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('moderation_note')
                    ->label('Catatan moderasi')
                    ->helperText('Catatan ini dapat dilihat oleh pengirim jika kiriman ditolak.')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
