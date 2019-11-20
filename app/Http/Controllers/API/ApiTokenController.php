<?php

namespace App\Http\Controllers\API;

use App\Device;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function destroy(Request $request)
    {
        Device::where('device_token', $request->input('token'))
            ->update([
                'device_token' => null
            ]);

        return [
            "success" => true
        ];
    }
}
