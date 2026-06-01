<?php

namespace Tests\Feature\Api\V1;

use App\Actions\Urls\FetchUrlTitle;
use App\Actions\Urls\SendUrlToDevice;
use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_list_their_recent_pushes_newest_first(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        $first = Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);
        $latest = Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);

        // Another user's push must not leak in.
        $other = User::factory()->create();
        Url::factory()->create([
            'user_id' => $other->id,
            'device_id' => Device::factory()->create(['user_id' => $other->id])->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson(route('api.v1.urls.index'))
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $latest->id)
            ->assertJsonPath('data.1.id', $first->id)
            ->assertJsonPath('data.0.device.id', $device->id)
            ->assertJsonStructure([
                'data' => [['id', 'url', 'title', 'device_id', 'device' => ['id', 'name']]],
                'meta' => ['next_cursor'],
            ]);
    }

    public function test_storing_a_url_persists_it_and_pushes_to_the_device(): void
    {
        // Avoid the real HTTP title fetch and Firebase push.
        $this->mock(FetchUrlTitle::class, fn ($mock) => $mock->shouldReceive('handle')->once());
        $this->mock(SendUrlToDevice::class, fn ($mock) => $mock->shouldReceive('handle')->once());

        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson(route('api.v1.urls.store'), [
            'device_id' => $device->id,
            'url' => 'https://example.org',
        ])
            ->assertCreated()
            ->assertJson(['data' => ['url' => 'https://example.org', 'device_id' => $device->id]]);

        $this->assertDatabaseHas('urls', [
            'user_id' => $user->id,
            'device_id' => $device->id,
            'url' => 'https://example.org',
        ]);
    }

    public function test_a_url_cannot_be_stored_against_another_users_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => User::factory()->create()->id]);

        Sanctum::actingAs($user);

        $this->postJson(route('api.v1.urls.store'), [
            'device_id' => $device->id,
            'url' => 'https://example.org',
        ])->assertForbidden();
    }

    public function test_a_user_can_delete_their_url(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);
        $url = Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);

        Sanctum::actingAs($user);

        $this->deleteJson(route('api.v1.urls.destroy', $url))->assertNoContent();

        $this->assertSoftDeleted($url);
    }

    public function test_a_user_cannot_delete_another_users_url(): void
    {
        $owner = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $owner->id]);
        $url = Url::factory()->create(['user_id' => $owner->id, 'device_id' => $device->id]);

        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson(route('api.v1.urls.destroy', $url))->assertForbidden();

        $this->assertModelExists($url);
    }
}
