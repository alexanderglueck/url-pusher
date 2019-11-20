<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiDeviceController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->devices;
    }
}
