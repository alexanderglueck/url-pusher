<?php

namespace Tests\Feature;

use App\Actions\Urls\FetchUrlTitle;
use App\Actions\Urls\SendUrlToDevice;
use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlActionsTest extends TestCase
{
    use RefreshDatabase;

    private function urlFor(User $user, array $overrides = []): Url
    {
        $device = Device::factory()->create(['user_id' => $user->id]);

        return Url::factory()->create(array_merge([
            'user_id' => $user->id,
            'device_id' => $device->id,
        ], $overrides));
    }

    public function test_a_url_title_can_be_edited(): void
    {
        $user = User::factory()->create();
        $url = $this->urlFor($user, ['title' => 'Old']);

        $this->actingAs($user)
            ->patch(route('urls.update', $url), ['title' => 'New title'])
            ->assertRedirect();

        $this->assertSame('New title', $url->fresh()->title);
    }

    public function test_a_url_can_be_favorited_and_unfavorited(): void
    {
        $user = User::factory()->create();
        $url = $this->urlFor($user, ['is_favorite' => false]);

        $this->actingAs($user)->post(route('urls.favorite', $url))->assertRedirect();
        $this->assertTrue($url->fresh()->is_favorite);

        $this->actingAs($user)->post(route('urls.favorite', $url))->assertRedirect();
        $this->assertFalse($url->fresh()->is_favorite);
    }

    public function test_a_user_cannot_edit_another_users_url(): void
    {
        $url = $this->urlFor(User::factory()->create(), ['title' => 'Old']);

        $this->actingAs(User::factory()->create())
            ->patch(route('urls.update', $url), ['title' => 'Hijacked'])
            ->assertForbidden();

        $this->assertSame('Old', $url->fresh()->title);
    }

    public function test_pushing_to_all_devices_creates_one_push_per_linked_device(): void
    {
        $this->mock(FetchUrlTitle::class, fn ($mock) => $mock->shouldReceive('handle'));
        $this->mock(SendUrlToDevice::class, fn ($mock) => $mock->shouldReceive('handle'));

        $user = User::factory()->create();
        Device::factory()->count(2)->create(['user_id' => $user->id, 'device_token' => 'token']);
        Device::factory()->create(['user_id' => $user->id, 'device_token' => null]);

        $this->actingAs($user)
            ->post(route('urls.push-all'), ['url' => 'https://example.org'])
            ->assertRedirect();

        $this->assertSame(2, $user->urls()->count());
    }
}
