<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(Login::class, fn (Login $e) =>
            Log::info('Login berhasil', ['user_id' => $e->user->id, 'ip' => request()->ip()]));

        Event::listen(Failed::class, fn (Failed $e) =>
            Log::warning('Login gagal', ['email' => $e->credentials['email'] ?? null, 'ip' => request()->ip()]));

        Event::listen(Lockout::class, fn (Lockout $e) =>
            Log::warning('Terlalu banyak percobaan login', ['ip' => $e->request->ip()]));

        Event::listen(Logout::class, fn (Logout $e) =>
            Log::info('Logout', ['user_id' => $e->user?->id]));

        Event::listen(PasswordReset::class, fn (PasswordReset $e) =>
            Log::info('Password direset', ['user_id' => $e->user->id]));
    }
}