<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [AuthController::class, 'verify_logged_in'])->name('verify_logged_in');
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