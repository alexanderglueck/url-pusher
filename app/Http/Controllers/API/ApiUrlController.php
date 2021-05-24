<?php

namespace App\Http\Controllers\API;

use App\Url;
use App\Http\Controllers\Controller;
use App\Http\Requests\UrlStoreRequest;

class ApiUrlController extends Controller
{
    public function store(UrlStoreRequest $request)
    {
        $url = new Url($request->validated());
        $url->device_id = $request->input('device_id');

        $request->user()->urls()->save($url);

        return response("", 201);
    }
}
