<?php

namespace App\Filament\Resources\AdminUsers\Pages;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $admin = new User;
        $admin->forceFill([
            ...$data,
            'email_verified_at' => now(),
            'is_admin' => true,
        ])->save();

        return $admin;
    }
}
