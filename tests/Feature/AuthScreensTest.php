<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_all_auth_screens(): void
    {
        foreach (['/login', '/register', '/forgot-password', '/reset-password/demo-token?email=user@example.com'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_customer_can_register_and_open_profile(): void
    {
        $response = $this->post('/register', [
            'name' => 'Customer CALMATS',
            'email' => 'customer@example.com',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ]);

        $response->assertRedirect('/profile');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'is_admin' => false,
        ]);

        $this->get('/profile')
            ->assertOk()
            ->assertSee('Customer CALMATS');
    }

    public function test_customer_can_update_profile_and_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
        ]);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nama Baru',
                'email' => 'baru@example.com',
            ])
            ->assertRedirect();

        $this->actingAs($user->fresh())
            ->put('/profile/password', [
                'current_password' => 'password',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
        ]);
    }

    public function test_forgot_password_request_has_success_state(): void
    {
        Notification::fake();
        User::factory()->create(['email' => 'member@example.com']);

        $this->post('/forgot-password', ['email' => 'member@example.com'])
            ->assertSessionHas('status');
    }

}
