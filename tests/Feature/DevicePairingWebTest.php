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
