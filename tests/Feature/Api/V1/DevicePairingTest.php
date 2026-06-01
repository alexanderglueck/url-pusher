<?php

namespace Tests\Feature\Api\V1;

use App\Models\DevicePairing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DevicePairingTest extends TestCase
{
    use RefreshDatabase;

    private function codeFor(User $user, array $overrides = []): DevicePairing
    {
        return $user->devicePairings()->create(array_merge([
            'code' => DevicePairing::generateCode(),
            'expires_at' => now()->addMinutes(10),
        ], $overrides));
    }

    public function test_an_unauthenticated_device_can_pair_and_receives_a_token(): void
    {
        $user = User::factory()->create();
        $pairing = $this->codeFor($user);

        $this->postJson(route('api.v1.devices.pair'), [
            'code' => $pairing->code,
            'name' => 'Pixel 8',
            'token' => 'fcm-token',
        ])
            ->assertCreated()
            ->assertJsonStructure(['device' => ['id', 'name', 'can_push'], 'token'])
            ->assertJsonPath('token', fn ($token) => is_string($token) && $token !== '');

        $this->assertDatabaseHas('devices', [
            'user_id' => $user->id,
            'name' => 'Pixel 8',
            'device_token' => 'fcm-token',
        ]);
        $this->assertNotNull($pairing->fresh()->device_id);
    }

    public function test_an_authenticated_matching_user_pairs_without_a_new_token(): void
    {
        $user = User::factory()->create();
        $pairing = $this->codeFor($user);

        Sanctum::actingAs($user);

        $this->postJson(route('api.v1.devices.pair'), [
            'code' => $pairing->code,
            'name' => 'Pixel 8',
            'token' => 'fcm-token',
        ])
            ->assertCreated()
            ->assertJsonPath('token', null);

        $this->assertDatabaseHas('devices', ['user_id' => $user->id, 'name' => 'Pixel 8']);
    }

    public function test_a_code_cannot_be_used_by_a_different_authenticated_user(): void
    {
        $owner = User::factory()->create();
        $pairing = $this->codeFor($owner);

        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.devices.pair'), [
            'code' => $pairing->code,
            'name' => 'Pixel 8',
            'token' => 'fcm-token',
        ])->assertStatus(422);

        $this->assertDatabaseCount('devices', 0);
        $this->assertNull($pairing->fresh()->device_id);
    }

    public function test_an_expired_code_is_rejected(): void
    {
        $user = User::factory()->create();
        $pairing = $this->codeFor($user, ['expires_at' => now()->subMinute()]);

        $this->postJson(route('api.v1.devices.pair'), [
            'code' => $pairing->code,
            'name' => 'Pixel 8',
            'token' => 'fcm-token',
        ])->assertStatus(422);

        $this->assertDatabaseCount('devices', 0);
    }

    public function test_a_code_can_only_be_used_once(): void
    {
        $user = User::factory()->create();
        $pairing = $this->codeFor($user);

        $payload = ['code' => $pairing->code, 'name' => 'Pixel 8', 'token' => 'fcm-token'];

        $this->postJson(route('api.v1.devices.pair'), $payload)->assertCreated();
        $this->postJson(route('api.v1.devices.pair'), $payload)->assertStatus(422);

        $this->assertDatabaseCount('devices', 1);
    }
}
