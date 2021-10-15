<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UrlStoreRequest;
use App\Models\Url;
use Embed\Embed;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiUrlController extends Controller
{
    public function store(UrlStoreRequest $request): Response
    {
        $url = new Url($request->validated());
        $url->device_id = $request->input('device_id');

        $info = Embed::create($url->url);
        $url->title = $info->title ?: $url->url;

        $request->user()->urls()->save($url);

        return response("", 201);
    }

    public function destroy(Request $request, Url $url): Response
    {
        abort_unless($url->user_id == $request->user()->id, 401);

        $url->delete();

        return response()->noContent();
    }
}
