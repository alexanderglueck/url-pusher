<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PairDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Models\DevicePairing;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DevicePairingController extends Controller
{
    /**
     * Pair a device using a code from a QR shown in the web app.
     *
     * The endpoint is intentionally unauthenticated so a fresh install can
     * pair without a prior login, but it still honours a bearer token when
     * one is sent:
     *  - no token  -> create the device and return a new API token
     *  - token set -> create the device, but only if it belongs to the same
     *                 user the code was issued for
     */
    public function pair(PairDeviceRequest $request): JsonResponse
    {
        $pairing = DevicePairing::claimable()
            ->where('code', $request->input('code'))
            ->first();

        if (! $pairing) {
            throw ValidationException::withMessages([
                'code' => ['This pairing code is invalid or has expired.'],
            ]);
        }

        $authenticatedUser = $request->user('sanctum');

        if ($authenticatedUser && $authenticatedUser->id !== $pairing->user_id) {
            throw ValidationException::withMessages([
                'code' => ['This QR code belongs to a different account.'],
            ]);
        }

        $user = $pairing->user;

        $device = $user->devices()->create(['name' => $request->input('name')]);
        $device->device_token = $request->input('token');
        $device->save();

        $pairing->update(['device_id' => $device->id]);

        return response()->json([
            'device' => new DeviceResource($device),
            'token' => $authenticatedUser
                ? null
                : $user->createToken($request->input('name'))->plainTextToken,
        ], 201);
    }
}
