<?php

namespace App\Filament\Resources\CommunityPosts\Tables;

use App\Models\CommunityPost;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommunityPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('user.name')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(55)
                    ->searchable()
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->sortable(),
                TextColumn::make('moderator.name')
                    ->label('Moderator')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'pending' => 'Menunggu moderasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (CommunityPost $record): bool => $record->status !== 'approved')
                    ->action(fn (CommunityPost $record) => $record->approve(auth()->user())),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (CommunityPost $record): bool => $record->status !== 'rejected')
                    ->action(fn (CommunityPost $record) => $record->reject(auth()->user())),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
