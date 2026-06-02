<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $devices = $request->user()->devices()->withDeviceToken()->latest()->get()
            ->map(fn ($device) => [
                'id' => $device->ulid,
                'name' => $device->name,
            ]);

        $search = trim((string) $request->input('q', ''));
        $favorites = $request->boolean('favorites');
        $limit = min(max($request->integer('limit', 15), 15), 200);

        $query = $request->user()->urls()->with('device')->latest()
            ->when($search !== '', fn ($q) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")))
            ->when($favorites, fn ($q) => $q->where('is_favorite', true));

        $total = (clone $query)->count();

        $urls = $query->limit($limit)->get()->map(fn (Url $url) => [
            'id' => $url->ulid,
            'url' => $url->url,
            'title' => $url->title,
            'description' => $url->description,
            'image' => $url->image,
            'push_status' => $url->push_status,
            'is_favorite' => $url->is_favorite,
            'created_at_human' => $url->created_at->diffForHumans(),
            'device' => [
                'id' => $url->device?->ulid,
                'name' => $url->device?->name,
                'can_push' => (bool) $url->device?->device_token,
            ],
        ]);

        return Inertia::render('Dashboard', [
            'devices' => $devices,
            'urls' => $urls,
            'filters' => ['q' => $search, 'favorites' => $favorites],
            'limit' => $limit,
            'canLoadMore' => $total > $limit,
        ]);
    }
}
