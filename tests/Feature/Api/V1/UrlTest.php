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

        $this->assertModelMissing($url);
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
