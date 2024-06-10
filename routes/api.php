<?php

use App\Http\Controllers\ConcentratorController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\MeasurementPointController;
use App\Http\Controllers\NoiseDataController;
use App\Http\Controllers\NoiseMeterController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SoundLimitController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\UserController;
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
        Route::patch("/{id}", [ConcentratorController::class, 'update']);
        Route::delete("/{id}", [ConcentratorController::class, 'delete']);

    });

    Route::prefix('/project')->group(function () {
        Route::post("/", [ProjectController::class, 'create']);
        Route::get("/", [ProjectController::class, 'index']);
        Route::get("/{id}", [ProjectController::class, 'get']);
        Route::patch("/{id}", [ProjectController::class, 'update']);
        Route::delete("/{id}", [ProjectController::class, 'delete']);

    });

    Route::prefix('/measurement-point')->group(function () {
        Route::post("/", [MeasurementPointController::class, 'create']);
        Route::get("/", [MeasurementPointController::class, 'index']);
        Route::get("/{id}", [MeasurementPointController::class, 'get']);
        Route::patch("/{id}", [MeasurementPointController::class, 'update']);
        Route::delete("/{id}", [MeasurementPointController::class, 'delete']);

    });

    Route::prefix('/contact')->group(function () {
        Route::post("/", [ContactsController::class, 'create']);
        Route::get("/", [ContactsController::class, 'index']);
        Route::get("/{id}", [ContactsController::class, 'get']);
        Route::patch("/{id}", [ContactsController::class, 'update']);
        Route::delete("/{id}", [ContactsController::class, 'delete']);

    });

    Route::prefix('/noise-data')->group(function () {
        Route::post("/", [NoiseDataController::class, 'create']);
        Route::get("/", [NoiseDataController::class, 'index']);
        Route::get("/{id}", [NoiseDataController::class, 'get']);
        Route::patch("/{id}", [NoiseDataController::class, 'update']);
        Route::delete("/{id}", [NoiseDataController::class, 'delete']);

    });

    Route::prefix('/sound-limit')->group(function () {
        Route::post("/", [SoundLimitController::class, 'create']);
        Route::get("/", [SoundLimitController::class, 'index']);
        Route::get("/{id}", [SoundLimitController::class, 'get']);
        Route::patch("/{id}", [SoundLimitController::class, 'update']);
        Route::delete("/{id}", [SoundLimitController::class, 'delete']);

    });

    Route::prefix('/user')->group(function () {
        Route::post("/", [UserController::class, 'create']);
        Route::get("/", [UserController::class, 'index']);
        Route::get("/{id}", [UserController::class, 'get']);
        Route::patch("/{id}", [UserController::class, 'update']);
        Route::delete("/{id}", [UserController::class, 'delete']);
        Route::post("/login", [UserController::class, 'login']);
    });
});
