<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiLoginController;
use App\Http\Controllers\API\ApiTokenController;
use App\Http\Controllers\API\ApiDeviceController;
use App\Http\Controllers\API\ApiUrlController;

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

Route::post('/session', [ApiLoginController::class, 'login'])->name('api.login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/remove-token', [ApiTokenController::class, 'destroy'])->name('api.token.destroy');
    Route::post('/attach-token', [ApiTokenController::class, 'store'])->name('api.token.store');
    Route::get('/devices', [ApiDeviceController::class, 'index'])->name('api.devices.index');
    Route::post('/urls', [ApiUrlController::class, 'store'])->name('api.url.store');
    Route::delete('/urls/{url}', [ApiUrlController::class, 'destroy'])->name('api.url.destroy');
});

