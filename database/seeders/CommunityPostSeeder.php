<?php

namespace Database\Seeders;

use App\Models\CommunityPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunityPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@calmats.test')->first();
        $member = User::where('email', 'member@calmats.test')->first();

        if (! $admin || ! $member || CommunityPost::exists()) {
            return;
        }

        CommunityPost::factory()->count(4)->approved($admin)->create(['user_id' => $member->id]);
        CommunityPost::factory()->count(2)->create(['user_id' => $member->id]);          // pending
        CommunityPost::factory()->count(1)->rejected($admin)->create(['user_id' => $member->id]);
    }
}