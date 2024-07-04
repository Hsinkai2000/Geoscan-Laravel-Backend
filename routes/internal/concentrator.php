<?php

use App\Http\Controllers\ConcentratorController;
use Illuminate\Support\Facades\Route;

Route::post("/concentrators", [ConcentratorController::class, 'create']);
Route::get("/concentrators/available", [ConcentratorController::class, 'get_available_concentrators'])->name('concentrator.get_available_concentrators');
Route::get("/concentrators", [ConcentratorController::class, 'index'])->name('concentrator_all');
Route::get("/concentrators/{id}", [ConcentratorController::class, 'get'])->name('concentrator');
Route::patch("/concentrators/{id}", [ConcentratorController::class, 'update']);
Route::delete("/concentrators/{id}", [ConcentratorController::class, 'delete']);