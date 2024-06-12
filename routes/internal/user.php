<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/users", [UserController::class, 'index']);
Route::get("/users/{id}", [UserController::class, 'get']);
Route::patch("/users/{id}", [UserController::class, 'update']);
Route::delete("/users/{id}", [UserController::class, 'delete']);
