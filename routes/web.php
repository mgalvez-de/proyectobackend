<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RamController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\ProcessorController;
use App\Http\Controllers\DeviceStatusController;
use App\Http\Controllers\HardwareFailureController;
use App\Http\Controllers\SoftwareFailureController;

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::resource('brands', BrandController::class);

    Route::resource('storages', StorageController::class);

    Route::get('/informatica', [DeviceController::class, 'index'])
        ->name('informatica.index');

    Route::resource('rams', RamController::class);

    Route::resource('devices', DeviceController::class);

    Route::resource('device-types', DeviceTypeController::class);

    Route::resource('processors', ProcessorController::class);

    Route::resource('device-statuses', DeviceStatusController::class);

    Route::resource('hardware-failures', HardwareFailureController::class);

    Route::resource('software-failures', SoftwareFailureController::class);
});


require __DIR__ . '/auth.php';
