<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZkTeco\AdmsController;
use App\Http\Controllers\ZkTeco\DeviceController;
use App\Http\Controllers\ZkTeco\MappingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('dashboard');
})->name('home');

Route::get('/iclock/cdata', [AdmsController::class, 'handshake']);
Route::post('/iclock/cdata', [AdmsController::class, 'attendance']);
Route::get('/iclock/getrequest', [AdmsController::class, 'getRequest']);
Route::post('/iclock/devicecmd', [AdmsController::class, 'deviceCommand']);

Route::middleware('web')->prefix('zkteco')->name('zkteco.')->group(function () {
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
});
