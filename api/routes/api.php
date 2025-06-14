<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
    Route::put('/clients/{client}', [ClientController::class, 'update']);
    Route::post('/clients', [ClientController::class, 'store']);

    Route::get('/user', fn() => auth()->user()->only(['email']));
});

