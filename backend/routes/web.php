<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// VULNERABILITY 36: No authentication required for logout
Route::post('/logout', [AuthController::class, 'logout']);

// VULNERABILITY 37: Admin functions without proper authorization
Route::post('/admin/force-logout', [AuthController::class, 'forceLogout']);
Route::post('/admin/logout-all-devices', [AuthController::class, 'logoutAllDevices']);

// Other auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
