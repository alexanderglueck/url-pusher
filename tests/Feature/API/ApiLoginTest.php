<?php

namespace Tests\Feature\API;

use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_login_with_valid_credentials()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'mail@example.org',
            'password' => Hash::make('secret-password')
        ]);

        $this->postJson(route('api.login'), [
            'email' => 'mail@example.org',
            'password' => 'secret-password'
        ])
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function test_a_guest_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'mail@example.org',
            'password' => Hash::make('secret-password')
        ]);

        $this->postJson(route('api.login'), [
            'email' => 'mail@example.org',
            'password' => 'wrong-password'
        ])
            ->assertStatus(401);
    }
}
