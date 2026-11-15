<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin: dibuat di semua environment. Password dari .env.
        $this->upsert(
            'admin@calmats.test',
            'Admin Calmats',
            env('SEED_ADMIN_PASSWORD', 'password'),
            true
        );

        // Member demo: hanya di luar production.
        if (! app()->environment('production')) {
            $this->upsert('member@calmats.test', 'Member Demo', 'password', false);
        }
    }

    private function upsert(string $email, string $name, string $password, bool $isAdmin): void
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $name,
            'password' => Hash::make($password),
            'is_admin' => $isAdmin,
            'email_verified_at' => now(),
        ])->save();
    }
}