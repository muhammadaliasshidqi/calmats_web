<?php

namespace Tests\Feature;

use App\Models\CommunityPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommunityPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_must_login_before_uploading_a_community_post(): void
    {
        $this->post('/community')->assertRedirect('/login');
    }

    public function test_customer_can_submit_a_photo_for_moderation(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/community', [
                'image' => UploadedFile::fake()->createWithContent(
                    'calmats-look.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')
                ),
                'caption' => 'Interpretasi tenang untuk bergerak di kota.',
            ])
            ->assertRedirect(route('community').'#share')
            ->assertSessionHas('status');

        $post = CommunityPost::query()->firstOrFail();

        $this->assertSame('pending', $post->status);
        $this->assertSame($user->id, $post->user_id);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_only_approved_posts_are_shown_in_the_public_feed(): void
    {
        $user = User::factory()->create(['name' => 'Komunitas CALMATS']);

        CommunityPost::query()->create([
            'user_id' => $user->id,
            'image' => 'community/pending.jpg',
            'caption' => 'Kiriman yang masih diperiksa.',
            'status' => 'pending',
        ]);

        CommunityPost::query()->create([
            'user_id' => $user->id,
            'image' => 'community/approved.jpg',
            'caption' => 'Kiriman yang sudah disetujui.',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $this->get('/community')
            ->assertOk()
            ->assertSee('Kiriman yang sudah disetujui.')
            ->assertDontSee('Kiriman yang masih diperiksa.');
    }

    public function test_admin_can_moderate_a_post_and_customer_cannot_open_moderation(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $post = CommunityPost::query()->create([
            'user_id' => $customer->id,
            'image' => 'community/submission.jpg',
            'caption' => 'Kiriman untuk diuji admin.',
            'status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get('/admin/community-posts')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('/admin/community-posts')
            ->assertOk()
            ->assertSee('Kiriman untuk diuji admin.');

        $post->approve($admin);

        $this->assertSame('approved', $post->fresh()->status);
        $this->assertSame($admin->id, $post->fresh()->moderated_by);
        $this->assertNotNull($post->fresh()->published_at);
    }
}
