<?php

namespace App\Http\Controllers\API;

use App\Device;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTokenController extends Controller
{
    public function store(Request $request)
    {
        Log::info('input', $request->all());

        Device::where('user_id', $request->user()->id)
            ->where('id', $request->input('id'))
            ->update([
                'device_token' => $request->input('token')
            ]);

        return [
            "success" => true
        ];
    }

    public function destroy(Request $request)
    {
        Log::info('input', $request->all());

        Device::where('device_token', $request->input('token'))
            ->update([
                'device_token' => null
            ]);

        return [
            "success" => true
        ];
    }
}
