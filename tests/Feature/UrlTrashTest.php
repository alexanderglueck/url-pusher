<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UrlTrashTest extends TestCase
{
    use RefreshDatabase;

    private function urlFor(User $user): Url
    {
        $device = Device::factory()->create(['user_id' => $user->id]);

        return Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);
    }

    public function test_deleted_urls_appear_in_the_trash(): void
    {
        $user = User::factory()->create();
        $url = $this->urlFor($user);
        $url->delete();

        $this->actingAs($user)
            ->get(route('urls.trash'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Urls/Trash')
                ->has('urls', 1)
                ->where('urls.0.id', $url->id)
            );
    }

    public function test_a_url_can_be_restored(): void
    {
        $user = User::factory()->create();
        $url = $this->urlFor($user);
        $url->delete();

        $this->actingAs($user)
            ->patch(route('urls.restore', $url->id))
            ->assertRedirect();

        $this->assertNotSoftDeleted($url);
    }

    public function test_a_url_can_be_permanently_deleted(): void
    {
        $user = User::factory()->create();
        $url = $this->urlFor($user);
        $url->delete();

        $this->actingAs($user)
            ->delete(route('urls.force-delete', $url->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('urls', ['id' => $url->id]);
    }

    public function test_a_user_cannot_restore_another_users_url(): void
    {
        $url = $this->urlFor(User::factory()->create());
        $url->delete();

        $this->actingAs(User::factory()->create())
            ->patch(route('urls.restore', $url->id))
            ->assertNotFound();

        $this->assertSoftDeleted($url);
    }
}
