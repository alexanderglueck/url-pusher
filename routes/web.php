<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('home');

    Route::resource('devices', DeviceController::class)->except([
        'show'
    ]);

    Route::resource('urls', UrlController::class)->only([
        'store',
        'destroy'
    ]);
});

Route::redirect('/.well-known/change-password', '/user/profile');
