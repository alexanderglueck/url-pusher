<?php

namespace Tests\Feature\API;

use App\Device;
use App\Url;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_post_an_url()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $device = Device::factory()->create([
            'user_id' => $user->id
        ]);

        $exampleUrl = 'https://example.org';

        $this->actingAs($user, 'api')
            ->postJson(route('api.url.store'), [
                'device_id' => $device->id,
                'url' => $exampleUrl
            ])
            ->assertStatus(201);

        $this->assertDatabaseHas('urls', [
            'user_id' => $user->id,
            'device_id' => $device->id,
            'url' => $exampleUrl
        ]);
    }

    public function test_an_authenticated_user_can_delete_an_url()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $device = Device::factory()->create([
            'user_id' => $user->id
        ]);

        $url = Url::factory()->create([
            'user_id' => $user->id,
            'device_id' => $device->id
        ]);

        $this->assertModelExists($url);

        $this->actingAs($user, 'api')
            ->deleteJson(route('api.url.destroy', $url))
            ->assertNoContent();

        $this->assertModelMissing($url);
    }
}
