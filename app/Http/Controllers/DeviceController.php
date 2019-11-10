<?php

namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests\DeviceDeleteRequest;
use App\Http\Requests\DeviceStoreRequest;
use App\Http\Requests\DeviceUpdateRequest;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('devices.index', [
            'devices' => $request->user()->devices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('devices.create', [
            'device' => new Device
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceStoreRequest $request)
    {
        $request->user()->devices()->create($request->validated());

        return redirect()->route('devices.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Device $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        return view('devices.edit', [
            'device' => $device
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Device $device
     * @return \Illuminate\Http\Response
     */
    public function update(DeviceUpdateRequest $request, Device $device)
    {
        $device->update($request->validated());

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Device $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceDeleteRequest $request, Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index');
    }
}
