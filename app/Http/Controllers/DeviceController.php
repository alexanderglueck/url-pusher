<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceDeleteRequest;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;
use App\Models\DevicePairing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeviceController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Devices/Index', [
            'devices' => $request->user()->devices()->get()
                ->map(fn (Device $device) => [
                    'id' => $device->id,
                    'name' => $device->name,
                    'can_push' => (bool) $device->device_token,
                ]),
        ]);
    }

    public function create(Request $request): Response
    {
        $pairing = $request->user()->devicePairings()->create([
            'code' => DevicePairing::generateCode(),
            'expires_at' => now()->addMinutes(DevicePairing::TTL_MINUTES),
        ]);

        $pairUrl = rtrim(config('app.mobile_url'), '/').'/api/v1/devices/pair';

        return Inertia::render('Devices/Create', [
            'pairing' => [
                // A scannable URL: the app reads `code` from the query string
                // and derives the API base from the URL's origin.
                'payload' => $pairUrl.'?code='.urlencode($pairing->code),
                'status_url' => route('devices.pairings.status', $pairing->code),
            ],
        ]);
    }

    public function pairingStatus(Request $request, string $code): JsonResponse
    {
        $pairing = $request->user()->devicePairings()->where('code', $code)->firstOrFail();

        return response()->json([
            'paired' => $pairing->isClaimed(),
        ]);
    }

    public function store(DeviceStoreRequest $request): RedirectResponse
    {
        $request->user()->devices()->create($request->validated());

        return redirect()->route('devices.index');
    }

    public function edit(Device $device): Response
    {
        return Inertia::render('Devices/Edit', [
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
            ],
        ]);
    }

    public function update(DeviceUpdateRequest $request, Device $device): RedirectResponse
    {
        $device->update($request->validated());

        return back();
    }

    public function destroy(DeviceDeleteRequest $request, Device $device): RedirectResponse
    {
        $device->delete();

        return redirect()->route('devices.index');
    }
}
