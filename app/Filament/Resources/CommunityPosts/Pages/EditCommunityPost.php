<?php

namespace App\Filament\Resources\CommunityPosts\Pages;

use App\Filament\Resources\CommunityPosts\CommunityPostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCommunityPost extends EditRecord
{
    protected static string $resource = CommunityPostResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) !== $this->record->status) {
            if (($data['status'] ?? null) === 'pending') {
                $data['moderated_by'] = null;
                $data['moderated_at'] = null;
                $data['published_at'] = null;
            } else {
                $data['moderated_by'] = auth()->id();
                $data['moderated_at'] = now();
                $data['published_at'] = ($data['status'] ?? null) === 'approved'
                    ? ($this->record->published_at ?? now())
                    : null;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
