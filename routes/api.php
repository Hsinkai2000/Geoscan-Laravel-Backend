<?php

use App\Http\Controllers\NoiseMeterController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TwilioController;
use Illuminate\Support\Facades\Route;

Route::post("/input", [PagesController::class, 'input']);
Route::post("/twilio/callback", [TwilioController::class, 'callback']);

Route::prefix('/api')->group(function () {
    Route::post("/noisemeter/", [NoiseMeterController::class, 'create']);
});
