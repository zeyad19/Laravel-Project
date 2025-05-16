<?php

use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Routes بدون Authentication
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);

// Routes محمية بـ Authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    Route::post('/jobs/{id}/approve', [JobController::class, 'approve']);
    Route::post('/jobs/{id}/reject', [JobController::class, 'reject']);
    Route::get('/jobs/{id}/applications', [JobController::class, 'getApplications']);
    Route::post('/jobs/{id}/apply', [JobController::class, 'apply']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/{id}/suspend', [UserController::class, 'suspend']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});