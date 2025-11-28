<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\AuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

// Protected routes (require authentication)
Route::middleware(\App\Http\Middleware\AuthenticateApiToken::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Programs routes
Route::get('/programs/search', [ProgramController::class, 'search']);
Route::get('/programs/{id}', [ProgramController::class, 'show']);

