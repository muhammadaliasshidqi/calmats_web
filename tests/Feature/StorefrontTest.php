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
            'price' => 249000,
            'compare_at_price' => 299000,
            'colors' => [
                ['name' => 'Black', 'hex' => '#191613'],
                ['name' => 'Natural', 'hex' => '#d8cdbf'],
            ],
            'variant_stock' => [
                ['color' => 'Black', 'size' => 'S', 'stock' => 4],
            ],
        ]);

        $this->get('/shop/essential-boxy-tee')
            ->assertOk()
            ->assertSee('Essential Boxy Tee')
            ->assertSee('Black')
            ->assertSee('Promo')
            ->assertSee('Rp 299.000')
            ->assertSee('stockFor', false)
            ->assertSee('x-on:click="$store.cart.add(', false);
    }

    public function test_unknown_product_returns_not_found(): void
    {
        $this->get('/shop/produk-tidak-ada')->assertNotFound();
    }

    public function test_catalog_can_search_filter_and_sort_products(): void
    {
        Product::factory()->create([
            'slug' => 'linen-overshirt',
            'name' => 'Linen Overshirt',
            'category' => 'Outerwear',
            'material' => 'Linen',
            'price' => 599000,
        ]);
        Product::factory()->create([
            'slug' => 'everyday-tee',
            'name' => 'Everyday Tee',
            'category' => 'Tops',
            'material' => 'Cotton',
            'price' => 199000,
        ]);

        $this->get('/shop?q=Linen')
            ->assertOk()
            ->assertSee('Linen Overshirt')
            ->assertDontSee('Everyday Tee');

        $this->get('/shop?category=Outerwear')
            ->assertOk()
            ->assertSee('Linen Overshirt')
            ->assertDontSee('Everyday Tee');

        $this->get('/shop?sort=price_desc')
            ->assertOk()
            ->assertSeeInOrder(['Linen Overshirt', 'Everyday Tee']);
    }

    public function test_catalog_uses_accessible_custom_sort_dropdown(): void
    {
        $this->get('/shop')
            ->assertOk()
            ->assertSee('role="listbox"', false)
            ->assertSee('aria-haspopup="listbox"', false)
            ->assertDontSee('<select name="sort"', false);
    }

    public function test_catalog_loads_six_products_then_can_show_more(): void
    {
        Product::factory()->count(7)->sequence(
            fn ($sequence) => [
                'slug' => "product-{$sequence->index}",
                'name' => "Product {$sequence->index}",
            ],
        )->create();

        $this->get('/shop')
            ->assertOk()
            ->assertSee('Menampilkan 6 dari 7 produk')
            ->assertSee('Muat lebih banyak')
            ->assertDontSee('Product 6');

        $this->get('/shop?limit=12')
            ->assertOk()
            ->assertSee('Menampilkan 7 dari 7 produk')
            ->assertSee('Product 6')
            ->assertDontSee('Muat lebih banyak');
    }

    public function test_cart_uses_whatsapp_handoff_with_copy_fallback(): void
    {
        config()->set('calmats.whatsapp_url', null);
        $this->get('/cart')
            ->assertOk()
            ->assertSee('Salin ringkasan pesanan');

        config()->set('calmats.whatsapp_url', 'https://wa.me/628123456789');
        $this->get('/cart')
            ->assertOk()
            ->assertSee('Pesan via WhatsApp');
    }

    public function test_login_requires_valid_credentials(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    }
}
