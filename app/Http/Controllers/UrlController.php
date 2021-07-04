<?php

namespace App\Http\Controllers;

use App\Url;
use Embed\Embed;
use LaravelFCM\Facades\FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UrlStoreRequest;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UrlStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UrlStoreRequest $request)
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

    /**
     * Display the specified resource.
     *
     * @param \App\Url $url
     * @return \Illuminate\Http\Response
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Url $url
     * @return \Illuminate\Http\Response
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Url $url
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Url $url
     * @return \Illuminate\Http\Response
     */
    public function destroy(Url $url)
    {
        $url->delete();

        return redirect()->route('home');
    }

    private function sendToDevice(Url $url)
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

    private function getMetaData(Url $url)
    {
        $info = Embed::create($url->url);

        $url->title = $info->title ?: $url->url;
        $url->save();
    }
}
