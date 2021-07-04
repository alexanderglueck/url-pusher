<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlStoreRequest;
use App\Url;
use Embed\Embed;

class ApiUrlController extends Controller
{
    public function store(UrlStoreRequest $request)
    {
        $url = new Url($request->validated());
        $url->device_id = $request->input('device_id');

        $info = Embed::create($url->url);
        $url->title = $info->title ?: $url->url;

        $request->user()->urls()->save($url);

        return response("", 201);
    }

    public function destroy(Url $url)
    {
        $url->delete();

        return response()->noContent();
    }
}
