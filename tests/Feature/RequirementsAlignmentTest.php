<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequirementsAlignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_products_are_hidden_from_catalog_detail_and_api(): void
    {
        $draft = Product::factory()->create([
            'slug' => 'draft-article',
            'name' => 'Draft Article',
            'is_active' => false,
        ]);

        $this->get('/shop')->assertOk()->assertDontSee($draft->name);
        $this->get("/shop/{$draft->slug}")->assertNotFound();
        $this->getJson("/api/products/{$draft->slug}")->assertNotFound();
    }

    public function test_catalog_filters_ready_preorder_and_sold_out_products(): void
    {
        Product::factory()->create([
            'name' => 'Ready Article',
            'variant_stock' => [['color' => 'Black', 'size' => 'M', 'stock' => 3]],
        ]);
        Product::factory()->create([
            'name' => 'Preorder Article',
            'is_preorder' => true,
            'stock_quantity' => 0,
            'variant_stock' => [['color' => 'Black', 'size' => 'M', 'stock' => 0]],
        ]);
        Product::factory()->create([
            'name' => 'Sold Out Article',
            'is_preorder' => false,
            'stock_quantity' => 0,
            'variant_stock' => [['color' => 'Black', 'size' => 'M', 'stock' => 0]],
        ]);

        $this->get('/shop?availability=ready')
            ->assertSee('Ready Article')
            ->assertDontSee('Preorder Article')
            ->assertDontSee('Sold Out Article');

        $this->get('/shop?availability=preorder')
            ->assertSee('Preorder Article')
            ->assertDontSee('Sold Out Article');

        $this->get('/shop?availability=sold_out')
            ->assertSee('Sold Out Article')
            ->assertDontSee('Ready Article');
    }

    public function test_variant_and_checkout_validation_use_current_server_data(): void
    {
        $product = Product::factory()->create([
            'slug' => 'server-truth-tee',
            'name' => 'Server Truth Tee',
            'price' => 175000,
            'colors' => [['name' => 'Black', 'hex' => '#191613', 'image' => 'products/colors/black.jpg']],
            'sizes' => ['M'],
            'variant_stock' => [['color' => 'Black', 'size' => 'M', 'stock' => 2]],
        ]);

        $this->postJson("/shop/{$product->slug}/validate-variant", [
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 8,
        ])
            ->assertOk()
            ->assertJsonPath('item.price', 175000)
            ->assertJsonPath('quantity', 2);

        $this->postJson('/cart/validate', [
            'items' => [[
                'slug' => $product->slug,
                'color' => 'Black',
                'size' => 'M',
                'quantity' => 8,
                'price' => 120000,
            ]],
        ])
            ->assertOk()
            ->assertJsonPath('changed', true)
            ->assertJsonPath('can_checkout', true)
            ->assertJsonPath('items.0.price', 175000)
            ->assertJsonPath('items.0.quantity', 2);
    }

    public function test_admin_whatsapp_setting_is_used_by_cart_and_footer(): void
    {
        SiteSetting::query()->create(['whatsapp_number' => '+6281234567890']);

        $this->get('/cart')
            ->assertOk()
            ->assertSee('https://wa.me/6281234567890', false);

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin/site-settings')
            ->assertOk()
            ->assertSee('+6281234567890');
    }
}
