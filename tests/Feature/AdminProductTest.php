<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_product_admin(): void
    {
        $this->get('/admin/products')->assertRedirect('/admin/login');
    }

    public function test_guest_can_open_the_dedicated_admin_login(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Masuk ke control room')
            ->assertSee('Masuk sebagai admin')
            ->assertSee('Kembali ke website');
    }

    public function test_authenticated_user_can_open_product_admin(): void
    {
        Product::factory()->create(['name' => 'Produk Uji CALMATS']);

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin/products')
            ->assertOk()
            ->assertSee('Produk Uji CALMATS');
    }

    public function test_authenticated_user_can_open_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin')
            ->assertOk()
            ->assertSee('Control room')
            ->assertSee('Kelola katalog dan moderasi Community CALMATS dari satu tempat.');
    }

    public function test_customer_account_cannot_open_admin_panel(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get('/admin')
            ->assertForbidden();
    }
}
