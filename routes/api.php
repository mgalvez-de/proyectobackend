<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\ProcessorController;
use App\Http\Controllers\Api\RamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Índice: GET /api  -> lista los endpoints disponibles
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json([
        'api'     => 'Inventario de equipos',
        'version' => '1.0',
        'publica' => [
            'GET    /api/rams',
            'POST   /api/rams',
            'GET    /api/rams/{id}',
            'PUT    /api/rams/{id}',
            'DELETE /api/rams/{id}',
            'GET    /api/processors',
            'POST   /api/processors',
            'GET    /api/processors/{id}',
            'PUT    /api/processors/{id}',
            'DELETE /api/processors/{id}',
            'POST   /api/login',
        ],
        'privada' => [
            'nota'  => 'Requieren el header: Authorization: Bearer {token}',
            'rutas' => [
                'GET    /api/me',
                'POST   /api/logout',
                'GET    /api/devices',
                'POST   /api/devices',
                'GET    /api/devices/{id}',
                'PUT    /api/devices/{id}',
                'DELETE /api/devices/{id}  (solo admin)',
                'GET    /api/stats         (solo admin)',
            ],
        ],
    ]);
})->name('api.index');

/*
|--------------------------------------------------------------------------
| 1) API REST PÚBLICA  (sin middleware, cualquiera la consume)
|--------------------------------------------------------------------------
*/

Route::apiResource('rams', RamController::class)->names('api.rams');

Route::apiResource('processors', ProcessorController::class)->names('api.processors');

/*
|--------------------------------------------------------------------------
| Login: es público porque aquí se pide el token para la API privada
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

/*
|--------------------------------------------------------------------------
| 2) API REST PRIVADA  (middleware auth:sanctum -> Authorization: Bearer)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // CRUD de equipos: requiere token (el destroy se define abajo, sólo admin)
    Route::apiResource('devices', DeviceController::class)
        ->except(['destroy'])
        ->names('api.devices');

    /*
    |----------------------------------------------------------------------
    | Middleware encadenado: auth:sanctum + role:admin
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::delete('/devices/{device}', [DeviceController::class, 'destroy'])
            ->name('api.devices.destroy');

        Route::get('/stats', [DeviceController::class, 'stats'])
            ->name('api.stats');
    });
});
