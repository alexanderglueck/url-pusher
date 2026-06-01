<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Urls\SendUrlToDevice;
use App\Actions\Urls\StoreUrl;
use App\Http\Controllers\Controller;
use App\Http\Requests\UrlStoreRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UrlController extends Controller
{
    /**
     * List the authenticated user's most recent pushes, newest first.
     *
     * Cursor-paginated for infinite scrolling; pass the `cursor` query
     * parameter from the previous response's `meta.next_cursor`.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $urls = $request->user()->urls()
            ->with('device')
            ->orderByDesc('id')
            ->cursorPaginate(20);

        return UrlResource::collection($urls);
    }

    /**
     * Store a URL and push it to the chosen device.
     */
    public function store(UrlStoreRequest $request, StoreUrl $storeUrl, SendUrlToDevice $sendUrlToDevice): JsonResponse
    {
        $url = $storeUrl->handle($request->user(), $request->validated());

        $sendUrlToDevice->handle($url);

        return (new UrlResource($url))->response()->setStatusCode(201);
    }

    /**
     * Delete one of the authenticated user's URLs.
     */
    public function destroy(Request $request, Url $url): JsonResponse
    {
        abort_unless($url->user_id === $request->user()->id, 403);

        $url->delete();

        return response()->json(status: 204);
    }
}
