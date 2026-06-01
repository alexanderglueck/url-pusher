<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceDeleteRequest;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use App\Models\Device;
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

    public function create(): Response
    {
        return Inertia::render('Devices/Create');
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
