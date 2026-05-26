<?php

namespace Tests\Unit;

use Kreait\Firebase\Messaging\CloudMessage;
use PHPUnit\Framework\TestCase;

class FirebaseCloudMessageTest extends TestCase
{
    public function test_url_controller_cloud_message_chain_is_buildable(): void
    {
        $userId = 1;

        $message = CloudMessage::new()
            ->withToken('device-token')
            ->withData([
                'title' => 'Example',
                'url' => 'https://example.com',
                'user_id' => (string) $userId,
            ]);

        $payload = $message->jsonSerialize();

        $this->assertSame('device-token', $payload['token']);
        $this->assertSame('Example', $payload['data']['title']);
        $this->assertSame('https://example.com', $payload['data']['url']);
        $this->assertSame('1', $payload['data']['user_id']);
    }

    public function test_cloud_message_rejects_non_string_data_values(): void
    {
        $this->expectException(\TypeError::class);

        CloudMessage::new()
            ->withToken('device-token')
            ->withData([
                'user_id' => 1,
            ]);
    }
}
