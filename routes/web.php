<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZkTeco\AdmsController;
use App\Http\Controllers\ZkTeco\DeviceController;
use App\Http\Controllers\ZkTeco\MappingController;
use App\Http\Controllers\ZkTeco\AttendanceLogController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return redirect()->route('home');
})->middleware('auth');

Route::get('/iclock/cdata', [AdmsController::class, 'handshake']);
Route::post('/iclock/cdata', [AdmsController::class, 'attendance']);
Route::get('/iclock/getrequest', [AdmsController::class, 'getRequest']);
Route::post('/iclock/devicecmd', [AdmsController::class, 'deviceCommand']);

Route::middleware(['web', 'auth'])->prefix('zkteco')->name('zkteco.')->group(function () {
    Route::resource('devices', DeviceController::class);
    Route::patch('devices/{device}/toggle', [DeviceController::class, 'toggleActive'])->name('devices.toggle');

    Route::prefix('devices/{device}/mappings')->name('devices.mappings.')->group(function () {
        Route::get('/', [MappingController::class, 'index'])->name('index');
        Route::get('/create', [MappingController::class, 'create'])->name('create');
        Route::post('/', [MappingController::class, 'store'])->name('store');
        Route::get('/{mapping}/edit', [MappingController::class, 'edit'])->name('edit');
        Route::put('/{mapping}', [MappingController::class, 'update'])->name('update');
        Route::delete('/{mapping}', [MappingController::class, 'destroy'])->name('destroy');
    });

    Route::resource('attendance-logs', AttendanceLogController::class);
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [SettingsController::class, 'edit'])->name('edit');
        Route::put('/profile', [SettingsController::class, 'update'])->name('update');
        Route::get('/password', [SettingsController::class, 'password'])->name('password');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
    });
});
