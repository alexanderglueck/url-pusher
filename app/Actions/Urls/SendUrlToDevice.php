<?php

namespace App\Actions\Urls;

use App\Models\Url;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class SendUrlToDevice
{
    /**
     * Push the given URL to its device through Firebase Cloud Messaging.
     *
     * Failures are logged and swallowed so a delivery problem never breaks
     * the request that triggered the push.
     */
    public function handle(Url $url): void
    {
        if (! $url->device?->device_token) {
            $url->forceFill([
                'push_status' => Url::PUSH_FAILED,
                'pushed_at' => now(),
            ])->save();

            return;
        }

        $message = CloudMessage::new()
            ->withToken($url->device->device_token)
            ->withData([
                'title' => (string) $url->title,
                'url' => (string) $url->url,
                'user_id' => (string) $url->user_id,
            ]);

        try {
            $result = Firebase::messaging()->send($message);

            Log::debug('Push response', [$result]);

            $status = Url::PUSH_SENT;
        } catch (FirebaseException $e) {
            // Only MessagingException carries structured errors(); the broader
            // FirebaseException markers (RuntimeException, InvalidArgumentException)
            // do not, so guard the call to avoid a fatal.
            Log::debug('Push response', [
                'errors' => $e instanceof MessagingException ? $e->errors() : [],
                'message' => $e->getMessage(),
            ]);

            $status = Url::PUSH_FAILED;
        }

        $url->forceFill([
            'push_status' => $status,
            'pushed_at' => now(),
        ])->save();
    }
}
