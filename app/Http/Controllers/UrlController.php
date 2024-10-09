<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Embed\Embed;
use Illuminate\Http\RedirectResponse;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\InvalidArgumentException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UrlStoreRequest;

class UrlController extends Controller
{
    public function store(UrlStoreRequest $request): RedirectResponse
    {
        $url = new Url($request->validated());
        $url->device_id = $request->input('device_id');

        $request->user()->urls()->save($url);

        // Grab title
        $this->getMetaData($url);

        // Send the notification to the device
        $this->sendToDevice($url);

        return redirect()->back();
    }

    public function destroy(Url $url): RedirectResponse
    {
        $url->delete();

        return redirect()->route('home');
    }

    private function sendToDevice(Url $url): void
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $url->device->device_token)
            ->withData([
                'title' => $url->title,
                'url' => $url->url,
                'user_id' => $url->user_id
            ]);

        try {
            $result = $messaging->send($message);

            Log::debug('Push response', [
                $result
            ]);
        } catch (MessagingException|FirebaseException|InvalidArgumentException $e) {
            Log::debug('Push response', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
        }
    }

    private function getMetaData(Url $url): void
    {
        $embed = new Embed();
        $info = $embed->get($url->url);

        $url->title = $info->title ?: $url->url;
        $url->save();
    }
}
