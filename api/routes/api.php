<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::get('/user', fn() => auth()->user()->only(['email']));
});

