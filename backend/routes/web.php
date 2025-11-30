<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/storage/uploads/{patientId}/rekam_medis/{filename}', [FileController::class, 'serveProtectedFile']);
