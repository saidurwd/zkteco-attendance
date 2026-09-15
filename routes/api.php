<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZkTeco\Api\DeviceController as DeviceApiController;
use App\Http\Controllers\ZkTeco\Api\AttendanceController as AttendanceApiController;
use App\Http\Controllers\ZkTeco\Api\MappingController as MappingApiController;
use App\Http\Controllers\Api\HikvisionEventController;

Route::middleware('auth:sanctum')->prefix('v1/zkteco')->name('api.v1.zkteco.')->group(function () {
    Route::apiResource('devices', DeviceApiController::class);

    Route::prefix('devices/{device}')->group(function () {
        Route::get('attendance', [AttendanceApiController::class, 'index']);
        Route::get('attendance/{attendance}', [AttendanceApiController::class, 'show']);

        Route::prefix('mappings')->group(function () {
            Route::get('/', [MappingApiController::class, 'index']);
            Route::post('/', [MappingApiController::class, 'store']);
            Route::put('/{mapping}', [MappingApiController::class, 'update']);
            Route::delete('/{mapping}', [MappingApiController::class, 'destroy']);
        });
    });
});

Route::post('/hikvision/events', [HikvisionEventController::class, 'receive'])->name('api.hikvision.events');
