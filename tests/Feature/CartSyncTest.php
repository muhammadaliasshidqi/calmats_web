<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_account_cart_data(): void
    {
        $this->getJson('/cart/data')->assertUnauthorized();
    }

    public function test_authenticated_cart_is_saved_and_limited_by_variant_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'slug' => 'quiet-overshirt',
            'colors' => [['name' => 'Black', 'hex' => '#191613']],
            'sizes' => ['M'],
            'variant_stock' => [
                ['color' => 'Black', 'size' => 'M', 'stock' => 3],
            ],
        ]);

        $this->actingAs($user)
            ->putJson('/cart/data', [
                'items' => [[
                    'slug' => $product->slug,
                    'color' => 'Black',
                    'size' => 'M',
                    'quantity' => 10,
                ]],
            ])
            ->assertOk()
            ->assertJsonPath('items.0.slug', 'quiet-overshirt')
            ->assertJsonPath('items.0.quantity', 3)
            ->assertJsonPath('items.0.stock', 3);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 3,
        ]);

        $this->actingAs($user)
            ->getJson('/cart/data')
            ->assertOk()
            ->assertJsonCount(1, 'items');
    }

    public function test_syncing_empty_cart_removes_account_cart_items(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->cartItems()->create([
            'product_id' => $product->id,
            'color' => 'Black',
            'size' => 'S',
            'quantity' => 1,
        ]);

        $this->actingAs($user)
            ->putJson('/cart/data', ['items' => []])
            ->assertOk()
            ->assertJsonCount(0, 'items');

        $this->assertDatabaseCount('cart_items', 0);
    }
}
