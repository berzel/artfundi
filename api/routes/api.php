<?php

use App\Http\Controllers\CreateClientController;
use App\Http\Controllers\EditClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/clients/{client}', [EditClientController::class, 'update']);
    Route::post('/clients', [CreateClientController::class, 'store']);

    Route::get('/user', fn() => auth()->user()->only(['email']));
});

