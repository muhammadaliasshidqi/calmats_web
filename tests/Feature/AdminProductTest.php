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

    public function test_authenticated_user_can_open_product_admin(): void
    {
        Product::factory()->create(['name' => 'Produk Uji CALMATS']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/products')
            ->assertOk()
            ->assertSee('Produk Uji CALMATS');
    }

    public function test_authenticated_user_can_open_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertOk()
            ->assertSee('Control room')
            ->assertSee('Kelola katalog CALMATS dari satu tempat.');
    }
}
