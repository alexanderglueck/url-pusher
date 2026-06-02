<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/features', [WelcomeController::class, 'features'])->name('features');
Route::get('/how-it-works', [WelcomeController::class, 'howItWorks'])->name('how-it-works');
Route::get('/faq', [WelcomeController::class, 'faq'])->name('faq');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('devices/pairings/{code}/status', [DeviceController::class, 'pairingStatus'])
        ->name('devices.pairings.status');

    Route::resource('devices', DeviceController::class)->except([
        'show',
    ]);

    Route::get('urls/trash', [UrlController::class, 'trash'])->name('urls.trash');
    Route::post('urls/push-all', [UrlController::class, 'pushAll'])->name('urls.push-all');
    Route::patch('urls/{ulid}/restore', [UrlController::class, 'restore'])->name('urls.restore');
    Route::delete('urls/{ulid}/force', [UrlController::class, 'forceDelete'])->name('urls.force-delete');
    Route::patch('urls/{url}', [UrlController::class, 'update'])->name('urls.update');
    Route::post('urls/{url}/favorite', [UrlController::class, 'favorite'])->name('urls.favorite');

    Route::resource('urls', UrlController::class)->only([
        'store',
        'destroy',
    ]);
});

Route::redirect('/.well-known/change-password', '/user/profile');
