<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return 'Login Admin CALMATS';
    }

    public function getHeading(): string|Htmlable|null
    {
        return 'Masuk ke control room';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString(
            'Gunakan akun admin untuk mengelola katalog, stok, dan publikasi produk. '
            . '<a href="'.e(url('/')).'" class="font-semibold text-primary-700 hover:underline">Kembali ke website</a>'
        );
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email admin')
            ->email()
            ->required()
            ->autocomplete('email')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata sandi')
            ->password()
            ->revealable()
            ->autocomplete('current-password')
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Masuk sebagai admin')
            ->submit('authenticate');
    }
}
