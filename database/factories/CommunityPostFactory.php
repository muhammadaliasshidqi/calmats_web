<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityPostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'image' => 'community/seed-' . fake()->numberBetween(1, 6) . '.jpg',
            'caption' => fake()->sentence(10),
            'status' => 'pending',
        ];
    }

    public function approved(?User $moderator = null): static
    {
        return $this->state(fn () => [
            'status' => 'approved',
            'moderated_by' => $moderator?->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);
    }

    public function rejected(?User $moderator = null): static
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'moderation_note' => 'Tidak sesuai ketentuan komunitas.',
            'moderated_by' => $moderator?->id,
            'moderated_at' => now(),
        ]);
    }
}