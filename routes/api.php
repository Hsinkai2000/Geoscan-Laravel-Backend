<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/input", [PagesController::class, 'input']);
Route::post("/twilio/callback", [TwilioController::class, 'callback']);

Route::post("/api/user", [UserController::class, 'create']);