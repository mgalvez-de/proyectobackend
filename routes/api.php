<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RamController;

Route::apiResource('rams', RamController::class)->names('api.rams');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
