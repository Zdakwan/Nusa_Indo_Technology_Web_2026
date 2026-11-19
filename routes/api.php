<?php

use App\Http\Controllers\Api\MobileProyekSyncController;
use Illuminate\Support\Facades\Route;

Route::prefix('internal')
    ->middleware(['mobile.api.key', 'throttle:60,1'])
    ->group(function () {
        Route::get('/proyek', [MobileProyekSyncController::class, 'index']);
        Route::get('/proyek/{id}', [MobileProyekSyncController::class, 'show'])
            ->whereNumber('id');
        Route::match(['patch', 'put'], '/proyek/{id}/progress', [MobileProyekSyncController::class, 'updateProgress'])
            ->whereNumber('id');
    });