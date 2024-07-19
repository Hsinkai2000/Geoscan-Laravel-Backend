<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get("/pdf/{id}", [PdfController::class, 'generatePdf'])->name('pdf.generatePdf');