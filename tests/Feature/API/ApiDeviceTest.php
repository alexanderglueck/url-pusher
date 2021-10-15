<?php

namespace Tests\Feature\API;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiDeviceTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_retrieve_devices()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $devices = Device::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user, 'api')
            ->getJson(route('api.devices.index'))
            ->assertStatus(200)
            ->assertJson($devices->toArray());
    }

}
