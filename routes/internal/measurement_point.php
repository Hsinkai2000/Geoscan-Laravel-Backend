<?php
use App\Http\Controllers\MeasurementPointController;
use Illuminate\Support\Facades\Route;

Route::get('/measurement_point', [MeasurementPointController::class, 'show'])->name('measurement_point.show');
Route::get("/measurement_point/{id}", [MeasurementPointController::class, 'show_by_project'])->name('measurement_point.show_by_project');
Route::post("/measurement_points", [MeasurementPointController::class, 'create'])->name('measurement_point.create');
Route::get("/measurement_points", [MeasurementPointController::class, 'index'])->name('measurement_point_all');
Route::get("/measurement_points/{id}", [MeasurementPointController::class, 'get'])->name('measurement_point');
Route::patch("/measurement_points/{id}", [MeasurementPointController::class, 'update'])->name('measurement_point.update');
Route::delete("/measurement_points/{id}", [MeasurementPointController::class, 'delete'])->name('measurement_point.delete');