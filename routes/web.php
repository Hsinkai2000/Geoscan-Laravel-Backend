<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

    require __DIR__ . '/internal/project.php';
    require __DIR__ . '/internal/soundlimit.php';
    require __DIR__ . '/internal/user.php';
    require __DIR__ . '/internal/noise_data.php';
    require __DIR__ . '/internal/contact.php';
    require __DIR__ . '/internal/measurement_point.php';
    require __DIR__ . '/internal/contact.php';
    require __DIR__ . '/internal/concentrator.php';
    require __DIR__ . '/internal/noise_meter.php';
    require __DIR__ . '/internal/pdf.php';
});