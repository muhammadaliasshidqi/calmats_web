<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $configuredUrl = (string) config('calmats.whatsapp_url');
        preg_match('/(?:wa\.me\/|\+)(\d{8,15})/', $configuredUrl, $matches);

        SiteSetting::query()->firstOrCreate([], [
            'whatsapp_number' => isset($matches[1]) ? '+'.$matches[1] : null,
        ]);
    }
}
