<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Google
Route::post('/googleOAuth', [AuthController::class, 'googleOAuth']);

//
Route::middleware('auth:sanctum')->group(function() {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/fetch', [AuthController::class, 'fetch']);

    // User
    Route::middleware(['isadmin'])->group(function () {
        Route::prefix('/user')->group(function () {
            Route::get('/getUserList', [UserController::class, 'getUserList']); 
        });
    });
});

