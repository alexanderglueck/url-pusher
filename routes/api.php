<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\UrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
|
| The mobile API. It is authenticated with Laravel Sanctum personal access
| tokens and shares its domain logic (storing URLs, pushing to devices)
| with the web application through the App\Actions\Urls action classes.
|
| A future v2 would be a copy of this block under its own namespace so the
| v1 contract the mobile app depends on stays frozen.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');

        Route::get('devices', [DeviceController::class, 'index'])->name('devices.index');
        Route::post('devices/{device}/token', [DeviceController::class, 'attachToken'])->name('devices.token.store');
        Route::delete('devices/{device}/token', [DeviceController::class, 'detachToken'])->name('devices.token.destroy');

        Route::post('urls', [UrlController::class, 'store'])->name('urls.store');
        Route::delete('urls/{url}', [UrlController::class, 'destroy'])->name('urls.destroy');
    });
});
