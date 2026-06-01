<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\DevicePairing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DevicePairingWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_create_page_issues_a_pairing_code(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('devices.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Devices/Create')
                ->has('pairing.payload')
                ->has('pairing.status_url')
            );

        $this->assertDatabaseCount('device_pairings', 1);
        $this->assertDatabaseHas('device_pairings', ['user_id' => $user->id, 'device_id' => null]);
    }

    public function test_the_qr_payload_uses_the_mobile_url(): void
    {
        config(['app.mobile_url' => 'http://192.168.1.50:8088']);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('devices.create'));

        $pairing = $response->viewData('page')['props']['pairing'];

        $this->assertStringStartsWith(
            'http://192.168.1.50:8088/api/v1/devices/pair?code=',
            $pairing['payload']
        );
    }

    public function test_pairing_status_reflects_when_a_device_is_claimed(): void
    {
        $user = User::factory()->create();
        $pairing = $user->devicePairings()->create([
            'code' => DevicePairing::generateCode(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->actingAs($user)
            ->getJson(route('devices.pairings.status', $pairing->code))
            ->assertOk()
            ->assertJson(['paired' => false]);

        $pairing->update(['device_id' => Device::factory()->create(['user_id' => $user->id])->id]);

        $this->actingAs($user)
            ->getJson(route('devices.pairings.status', $pairing->code))
            ->assertOk()
            ->assertJson(['paired' => true]);
    }

    public function test_pairing_status_is_scoped_to_the_owner(): void
    {
        $pairing = User::factory()->create()->devicePairings()->create([
            'code' => DevicePairing::generateCode(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->actingAs(User::factory()->create())
            ->getJson(route('devices.pairings.status', $pairing->code))
            ->assertNotFound();
    }
}
