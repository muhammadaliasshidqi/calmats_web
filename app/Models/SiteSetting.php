<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['whatsapp_number'];

    public static function current(): ?self
    {
        if (! Schema::hasTable('site_settings')) {
            return null;
        }

        return static::query()->oldest('id')->first();
    }

    public static function whatsappUrl(): ?string
    {
        $number = static::current()?->whatsapp_number;

        if (filled($number)) {
            return 'https://wa.me/'.ltrim($number, '+');
        }

        return config('calmats.whatsapp_url');
    }
}
