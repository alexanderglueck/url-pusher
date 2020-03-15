<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiLoginController;
use App\Http\Controllers\API\ApiTokenController;
use App\Http\Controllers\API\ApiDeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/session', [ApiLoginController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/remove-token', [ApiTokenController::class, 'destroy']);
    Route::post('/attach-token', [ApiTokenController::class, 'store']);
    Route::get('/devices', [ApiDeviceController::class, 'index']);
});

