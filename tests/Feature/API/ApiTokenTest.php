<?php

namespace Tests\Feature\API;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_store_a_device_token()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $device = Device::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user, 'api')
            ->postJson(route('api.token.store'), [
                'id' => $device->id,
                'token' => 'custom-token'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('devices', [
            'user_id' => $user->id,
            'id' => $device->id,
            'device_token' => 'custom-token'
        ]);
    }

    public function test_an_authenticated_user_can_unset_a_device_token()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $device = Device::factory()->create([
            'user_id' => $user->id,
            'device_token' => 'custom-token'
        ]);

        $this->assertDatabaseHas('devices', [
            'user_id' => $user->id,
            'id' => $device->id,
            'device_token' => 'custom-token'
        ]);

        $this->actingAs($user, 'api')
            ->postJson(route('api.token.destroy'), [
                'token' => 'custom-token'
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('devices', [
            'user_id' => $user->id,
            'id' => $device->id,
            'device_token' => 'custom-token'
        ]);
    }
}
