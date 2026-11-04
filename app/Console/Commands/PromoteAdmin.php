<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'calmats:admin {email : Email akun yang akan dijadikan admin}';

    protected $description = 'Memberikan akses dashboard admin CALMATS kepada sebuah akun';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Akun dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        $user->forceFill(['is_admin' => true])->save();
        $this->info("{$user->email} sekarang dapat mengakses dashboard admin.");

        return self::SUCCESS;
    }
}
