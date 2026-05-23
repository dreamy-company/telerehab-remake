<?php

use App\Http\Controllers\Api\Patient\AuthController;
use App\Http\Controllers\Api\Patient\DashboardController;
use App\Http\Controllers\Api\Patient\RehabilitationController;
use App\Http\Controllers\Api\Patient\ExerciseController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('patient')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (requires Sanctum token)
Route::prefix('patient')->middleware(['auth:sanctum', 'role:patient'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/dashboard/request-consultation', [DashboardController::class, 'requestConsultation']);

    // Rehabilitation list
    Route::get('/rehabilitations', [RehabilitationController::class, 'index']);

    // Exercise (per rehab routine)
    Route::get('/rehabilitations/{id}/exercise', [ExerciseController::class, 'index']);
    Route::post('/rehabilitations/{id}/exercise/upload', [ExerciseController::class, 'uploadVideo']);
});