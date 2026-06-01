<?php

namespace Tests\Feature\Api\V1;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_list_their_devices(): void
    {
        $user = User::factory()->create();
        Device::factory()->count(3)->create(['user_id' => $user->id]);
        Device::factory()->create(['user_id' => User::factory()->create()->id]);

        Sanctum::actingAs($user);

        $this->getJson(route('api.v1.devices.index'))
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_a_user_can_attach_a_token_to_a_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson(route('api.v1.devices.token.store', $device), ['token' => 'fcm-token'])
            ->assertOk()
            ->assertJson(['data' => ['id' => $device->id, 'can_push' => true]]);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device_token' => 'fcm-token',
        ]);
    }

    public function test_a_user_can_detach_a_token_from_a_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => $user->id, 'device_token' => 'fcm-token']);

        Sanctum::actingAs($user);

        $this->deleteJson(route('api.v1.devices.token.destroy', $device))
            ->assertNoContent();

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device_token' => null,
        ]);
    }

    public function test_a_user_cannot_attach_a_token_to_another_users_device(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create(['user_id' => User::factory()->create()->id]);

        Sanctum::actingAs($user);

        $this->postJson(route('api.v1.devices.token.store', $device), ['token' => 'fcm-token'])
            ->assertForbidden();
    }
}
