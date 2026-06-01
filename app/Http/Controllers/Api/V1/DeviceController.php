<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DeviceController extends Controller
{
    /**
     * List the authenticated user's devices.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return DeviceResource::collection($request->user()->devices);
    }

    /**
     * Register (or replace) the Firebase token for one of the user's devices.
     */
    public function attachToken(Request $request, Device $device): DeviceResource
    {
        abort_unless($device->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $device->device_token = $validated['token'];
        $device->save();

        return new DeviceResource($device);
    }

    /**
     * Remove the Firebase token from one of the user's devices.
     */
    public function detachToken(Request $request, Device $device): JsonResponse
    {
        abort_unless($device->user_id === $request->user()->id, 403);

        $device->device_token = null;
        $device->save();

        return response()->json(status: 204);
    }
}
