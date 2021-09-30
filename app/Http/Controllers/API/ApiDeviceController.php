<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDeviceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $request->user()->devices;
    }
}
