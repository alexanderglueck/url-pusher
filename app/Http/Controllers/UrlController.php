<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Embed\Embed;
use Illuminate\Http\RedirectResponse;
use LaravelFCM\Facades\FCM;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UrlStoreRequest;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;

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
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => $url->title,
            'url' => $url->url,
            'user_id' => $url->user_id
        ]);

        $option = $optionBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($url->device->device_token, $option, null, $data);

        Log::debug('Push response', [
            'numSuccess' => $downstreamResponse->numberSuccess(),
            'numFailure' => $downstreamResponse->numberFailure(),
            'numModification' => $downstreamResponse->numberModification(),
            'delete' => $downstreamResponse->tokensToDelete(),
            'modify' => $downstreamResponse->tokensToModify(),
            'retry' => $downstreamResponse->tokensToRetry(),
            'error' => $downstreamResponse->tokensWithError()
        ]);
    }

    private function getMetaData(Url $url): void
    {
        $info = Embed::create($url->url);

        $url->title = $info->title ?: $url->url;
        $url->save();
    }
}
