<?php

namespace App\Http\Controllers;

use App\Actions\Urls\SendUrlToDevice;
use App\Actions\Urls\StoreUrl;
use App\Http\Requests\UrlStoreRequest;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function store(UrlStoreRequest $request, StoreUrl $storeUrl, SendUrlToDevice $sendUrlToDevice): RedirectResponse
    {
        $url = $storeUrl->handle($request->user(), $request->validated());

        $sendUrlToDevice->handle($url);

        return redirect()->back();
    }

    public function destroy(Request $request, Url $url): RedirectResponse
    {
        abort_unless($url->user_id === $request->user()->id, 403);

        $url->delete();

        return redirect()->route('dashboard');
    }
}
