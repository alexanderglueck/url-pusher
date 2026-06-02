<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_user_can_register_and_receives_a_token(): void
    {
        Event::fake();

        $this->postJson(route('api.v1.auth.register'), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.org',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
            'device_name' => 'pixel',
        ])
            ->assertCreated()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'ada@example.org']);
        Event::assertDispatched(Registered::class);
    }

    public function test_registration_rejects_a_duplicate_email(): void
    {
        User::factory()->create(['email' => 'ada@example.org']);

        $this->postJson(route('api.v1.auth.register'), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.org',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'correct-horse-battery',
        ])->assertStatus(422);
    }

    public function test_registration_requires_a_confirmed_password(): void
    {
        $this->postJson(route('api.v1.auth.register'), [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.org',
            'password' => 'correct-horse-battery',
            'password_confirmation' => 'different',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('users', ['email' => 'ada@example.org']);
    }

    public function test_a_user_can_obtain_a_token_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'mail@example.org',
            'password' => Hash::make('secret-password'),
        ]);

        $this->postJson(route('api.v1.auth.login'), [
            'email' => 'mail@example.org',
            'password' => 'secret-password',
            'device_name' => 'pixel',
        ])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name' => 'pixel',
        ]);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create([
            'email' => 'mail@example.org',
            'password' => Hash::make('secret-password'),
        ]);

        $this->postJson(route('api.v1.auth.login'), [
            'email' => 'mail@example.org',
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    public function test_the_authenticated_user_can_be_retrieved(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson(route('api.v1.auth.me'))
            ->assertOk()
            ->assertJson(['data' => ['id' => $user->ulid, 'email' => $user->email]]);
    }

    public function test_logging_out_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('pixel');

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson(route('api.v1.auth.logout'))
            ->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    }

    public function test_guests_cannot_access_protected_routes(): void
    {
        $this->getJson(route('api.v1.auth.me'))->assertUnauthorized();
    }
}
