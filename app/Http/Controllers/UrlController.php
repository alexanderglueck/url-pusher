<?php

namespace App\Http\Controllers;

use App\Actions\Urls\SendUrlToDevice;
use App\Actions\Urls\StoreUrl;
use App\Http\Requests\UrlStoreRequest;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UrlController extends Controller
{
    public function trash(Request $request): Response
    {
        $urls = $request->user()->urls()->onlyTrashed()->with('device')->latest('deleted_at')->get()
            ->map(fn (Url $url) => [
                'id' => $url->id,
                'url' => $url->url,
                'title' => $url->title,
                'image' => $url->image,
                'deleted_at_human' => $url->deleted_at->diffForHumans(),
                'device' => ['name' => $url->device?->name],
            ]);

        return Inertia::render('Urls/Trash', ['urls' => $urls]);
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $request->user()->urls()->onlyTrashed()->findOrFail($id)->restore();

        return back();
    }

    public function forceDelete(Request $request, int $id): RedirectResponse
    {
        $request->user()->urls()->onlyTrashed()->findOrFail($id)->forceDelete();

        return back();
    }

    public function store(UrlStoreRequest $request, StoreUrl $storeUrl, SendUrlToDevice $sendUrlToDevice): RedirectResponse
    {
        $url = $storeUrl->handle($request->user(), $request->validated());

        $sendUrlToDevice->handle($url);

        return redirect()->back();
    }

    public function update(Request $request, Url $url): RedirectResponse
    {
        abort_unless($url->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $url->update($validated);

        return back();
    }

    public function favorite(Request $request, Url $url): RedirectResponse
    {
        abort_unless($url->user_id === $request->user()->id, 403);

        $url->update(['is_favorite' => ! $url->is_favorite]);

        return back();
    }

    /**
     * Push a link to every device the user has linked.
     */
    public function pushAll(Request $request, StoreUrl $storeUrl, SendUrlToDevice $sendUrlToDevice): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'url', 'max:500'],
        ]);

        $request->user()->devices()->withDeviceToken()->get()->each(function ($device) use ($request, $storeUrl, $sendUrlToDevice, $validated) {
            $url = $storeUrl->handle($request->user(), [
                'url' => $validated['url'],
                'device_id' => $device->id,
            ]);

            $sendUrlToDevice->handle($url);
        });

        return back();
    }

    public function destroy(Request $request, Url $url): RedirectResponse
    {
        abort_unless($url->user_id === $request->user()->id, 403);

        $url->delete();

        return redirect()->route('dashboard');
    }
}
