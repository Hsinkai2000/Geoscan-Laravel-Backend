<?php
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get("/project", [ProjectController::class, 'show'])->name('project.show');
Route::post("/projects", [ProjectController::class, 'create']);
Route::get("/projects", [ProjectController::class, 'index'])->name('project_all');
Route::get("/projects/{id}", [ProjectController::class, 'get'])->name('project');
Route::patch("/projects/{id}", [ProjectController::class, 'update']);
Route::delete("/projects/{id}", [ProjectController::class, 'delete']);
