<?php

namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests\DeviceDeleteRequest;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request): View
    {
        SEOTools::setTitle('Devices  - ' . config('app.name'));
        SEOTools::setDescription('Manage your devices.');

        return view('devices.index', [
            'devices' => $request->user()->devices
        ]);
    }

    public function create(): View
    {
        SEOTools::setTitle('Create Device  - ' . config('app.name'));
        SEOTools::setDescription('Add a new device device.');

        return view('devices.create', [
            'device' => new Device
        ]);
    }

    public function store(DeviceStoreRequest $request): RedirectResponse
    {
        $request->user()->devices()->create($request->validated());

        return redirect()->route('devices.index');
    }

    public function edit(Device $device): View
    {
        SEOTools::setTitle('Edit Device  - ' . config('app.name'));
        SEOTools::setDescription('Edit your device.');

        return view('devices.edit', [
            'device' => $device
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
