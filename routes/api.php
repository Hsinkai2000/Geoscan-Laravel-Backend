<?php

use App\Http\Controllers\ConcentratorController;
use App\Http\Controllers\NoiseMeterController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;

Route::post("/input", [PagesController::class, 'input']);
Route::post("/twilio/callback", [TwilioController::class, 'callback']);

Route::prefix('/api')->group(function () {
    Route::prefix('/noisemeter')->group(function () {
        Route::post("/", [NoiseMeterController::class, 'create']);
        Route::get("/", [NoiseMeterController::class, 'index']);
        Route::get("/{id}", [NoiseMeterController::class, 'get']);
        Route::patch("/{id}", [NoiseMeterController::class, 'update']);
        Route::delete("/{id}", [NoiseMeterController::class, 'delete']);
    });

    Route::prefix('/concentrator')->group(function () {
        Route::post("/", [ConcentratorController::class, 'create']);
        Route::get("/", [ConcentratorController::class, 'index']);
        Route::get("/{id}", [ConcentratorController::class, 'get']);
    });
});
