<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_pages_are_available(): void
    {
        foreach (['/', '/shop', '/about', '/community', '/cart', '/login'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_product_detail_uses_the_catalog(): void
    {
        Product::factory()->create([
            'slug' => 'essential-boxy-tee',
            'name' => 'Essential Boxy Tee',
        ]);

        $this->get('/shop/essential-boxy-tee')
            ->assertOk()
            ->assertSee('Essential Boxy Tee')
            ->assertSee('x-data="{ size: \'S\', added: false }"', false)
            ->assertSee('x-on:click="$store.cart.add(', false);
    }

    public function test_unknown_product_returns_not_found(): void
    {
        $this->get('/shop/produk-tidak-ada')->assertNotFound();
    }

    public function test_login_requires_valid_credentials(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    }
}
