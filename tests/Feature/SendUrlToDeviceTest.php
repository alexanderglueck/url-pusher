<?php

namespace Tests\Feature;

use App\Actions\Urls\SendUrlToDevice;
use App\Models\Device;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\RuntimeException;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Mockery;
use Tests\TestCase;

class SendUrlToDeviceTest extends TestCase
{
    use RefreshDatabase;

    private function urlWithToken(): Url
    {
        $user = User::factory()->create();
        $device = Device::factory()->create([
            'user_id' => $user->id,
            'device_token' => 'device-token-123',
        ]);

        return Url::factory()->create([
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }

    public function test_a_firebase_runtime_exception_marks_the_push_failed_without_erroring(): void
    {
        $url = $this->urlWithToken();

        // RuntimeException implements FirebaseException but has no errors()
        // method — the catch block must not call errors() on it.
        $messaging = Mockery::mock(Messaging::class);
        $messaging->shouldReceive('send')->once()->andThrow(new RuntimeException('boom'));
        Firebase::shouldReceive('messaging')->once()->andReturn($messaging);

        app(SendUrlToDevice::class)->handle($url);

        $this->assertSame(Url::PUSH_FAILED, $url->fresh()->push_status);
        $this->assertNotNull($url->fresh()->pushed_at);
    }

    public function test_a_successful_send_marks_the_push_sent(): void
    {
        $url = $this->urlWithToken();

        $messaging = Mockery::mock(Messaging::class);
        $messaging->shouldReceive('send')->once()->andReturn(['name' => 'projects/x/messages/1']);
        Firebase::shouldReceive('messaging')->once()->andReturn($messaging);

        app(SendUrlToDevice::class)->handle($url);

        $this->assertSame(Url::PUSH_SENT, $url->fresh()->push_status);
    }

    public function test_a_device_without_a_token_fails_fast(): void
    {
        $user = User::factory()->create();
        $device = Device::factory()->create([
            'user_id' => $user->id,
            'device_token' => null,
        ]);
        $url = Url::factory()->create(['user_id' => $user->id, 'device_id' => $device->id]);

        app(SendUrlToDevice::class)->handle($url);

        $this->assertSame(Url::PUSH_FAILED, $url->fresh()->push_status);
    }
}
