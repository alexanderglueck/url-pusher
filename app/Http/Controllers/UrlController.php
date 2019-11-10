<?php

namespace App\Http\Controllers;

use App\Url;
use LaravelFCM\Facades\FCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UrlStoreRequest;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UrlStoreRequest $request)
    {
        $url = new Url($request->validated());
        $url->device_id = $request->input('device_id');

        $request->user()->urls()->save($url);

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
        //
    }

    private function sendToDevice(Url $url)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New push incoming');
        $notificationBuilder->setBody($url->url);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['url' => $url->url]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($url->device->device_token, $option, $notification, $data);

        // return Array - you must remove all this tokens in your database
        // $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        // $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        // $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        // $downstreamResponse->tokensWithError();

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
}
