<?php

namespace Tests\Feature;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Filament\Resources\AdminUsers\Pages\CreateAdminUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_admin_account_management(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/admin-users')
            ->assertOk()
            ->assertSee('Akun Admin')
            ->assertSee('Tambah akun admin');
    }

    public function test_customer_account_is_not_shown_in_admin_account_management(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create([
            'name' => 'Pelanggan Biasa',
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->get('/admin/admin-users')
            ->assertOk()
            ->assertDontSee($customer->name);
    }

    public function test_admin_can_create_another_admin_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(CreateAdminUser::class)
            ->fillForm([
                'name' => 'Admin Teman',
                'email' => 'teman@example.com',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newAdmin = User::query()->where('email', 'teman@example.com')->firstOrFail();

        $this->assertTrue($newAdmin->is_admin);
        $this->assertTrue(Hash::check('rahasia123', $newAdmin->password));
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin);

        $this->assertFalse(AdminUserResource::canDelete($admin));
    }
}
