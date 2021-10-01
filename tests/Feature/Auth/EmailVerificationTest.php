<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected string $verificationVerifyRouteName = 'verification.verify';

    protected function successfulVerificationRoute(): string
    {
        return route('home');
    }

    protected function verificationNoticeRoute(): string
    {
        return route('verification.notice');
    }

    protected function validVerificationVerifyRoute(User $user): string
    {
        return URL::signedRoute($this->verificationVerifyRouteName, [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]);
    }

    protected function invalidVerificationVerifyRoute(User $user): string
    {
        return route($this->verificationVerifyRouteName, [
            'id' => $user->id,
            'hash' => 'invalid-hash',
        ]);
    }

    protected function verificationResendRoute(): string
    {
        return route('verification.resend');
    }

    protected function loginRoute(): string
    {
        return route('login');
    }

    public function test_guest_cannot_see_the_verification_notice(): void
    {
        $response = $this->get($this->verificationNoticeRoute());

        $response->assertRedirect($this->loginRoute());
    }

    public function test_user_sees_the_verification_notice_when_not_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify');
    }

    public function test_verified_user_is_redirected_home_when_visiting_verification_notice_route(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());

        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function test_guest_cannot_see_the_verification_verify_route(): void
    {
        $user = User::factory()->create([
            'id' => 1,
            'email_verified_at' => null,
        ]);

        $response = $this->get($this->validVerificationVerifyRoute($user));

        $response->assertRedirect($this->loginRoute());
    }

    public function test_user_cannot_verify_others(): void
    {
        $user = User::factory()->create([
            'id' => 1,
            'email_verified_at' => null,
        ]);

        $user2 = User::factory()->create(['id' => 2, 'email_verified_at' => null]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user2));

        $response->assertForbidden();
        $this->assertFalse($user2->fresh()->hasVerifiedEmail());
    }

    public function test_user_is_redirected_to_correct_route_when_already_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user));

        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function test_forbidden_is_returned_when_signature_is_invalid_in_verification_verify_route(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get($this->invalidVerificationVerifyRoute($user));

        $response->assertStatus(403);
    }

    public function test_user_can_verify_themselves(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get($this->validVerificationVerifyRoute($user));

        $response->assertRedirect($this->successfulVerificationRoute());
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_guest_cannot_resend_a_verification_email(): void
    {
        $response = $this->post($this->verificationResendRoute());

        $response->assertRedirect($this->loginRoute());
    }

    public function test_user_is_redirected_to_correct_route_if_already_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->post($this->verificationResendRoute());

        $response->assertRedirect($this->successfulVerificationRoute());
    }

    public function test_user_can_resend_a_verification_email(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->from($this->verificationNoticeRoute())
            ->post($this->verificationResendRoute());

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect($this->verificationNoticeRoute());
    }
}
