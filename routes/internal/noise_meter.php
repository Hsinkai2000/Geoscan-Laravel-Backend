<?php

use App\Http\Controllers\NoiseMeterController;
use Illuminate\Support\Facades\Route;

Route::post("/noise_meters", [NoiseMeterController::class, 'create']);
Route::get("/noise_meters/available", [NoiseMeterController::class, 'get_available_noise_meters'])->name('noise_meter.get_available_noise_meters');
Route::get("/noise_meters", [NoiseMeterController::class, 'index'])->name('noise_meter_all');
Route::get("/noise_meters/{id}", [NoiseMeterController::class, 'get'])->name('noise_meter');
Route::patch("/noise_meters/{id}", [NoiseMeterController::class, 'update']);
Route::delete("/noise_meters/{id}", [NoiseMeterController::class, 'delete']);