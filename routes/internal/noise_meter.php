<?php

use App\Http\Controllers\NoiseMeterController;
use Illuminate\Support\Facades\Route;

Route::post("/noise_meters", [NoiseMeterController::class, 'create']);
Route::get("/noise_meters", [NoiseMeterController::class, 'index'])->name('noise_meter_all');
Route::get("/noise_meters/{id}", [NoiseMeterController::class, 'get'])->name('noise_meter');
Route::patch("/noise_meters/{id}", [NoiseMeterController::class, 'update']);
Route::delete("/noise_meters/{id}", [NoiseMeterController::class, 'delete']);
