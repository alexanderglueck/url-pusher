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
        $devices = $request->user()->devices()->withDeviceToken()->get()
            ->map(fn ($device) => [
                'id' => $device->id,
                'name' => $device->name,
            ]);

        $urls = $request->user()->urls()->latest()->limit(15)->with('device')->get()
            ->map(fn (Url $url) => [
                'id' => $url->id,
                'url' => $url->url,
                'title' => $url->title,
                'created_at_human' => $url->created_at->diffForHumans(),
                'device' => [
                    'id' => $url->device?->id,
                    'name' => $url->device?->name,
                    'can_push' => (bool) $url->device?->device_token,
                ],
            ]);

        return Inertia::render('Dashboard', [
            'devices' => $devices,
            'urls' => $urls,
        ]);
    }
}
