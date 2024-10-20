<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Google
Route::post('/googleOAuth', [AuthController::class, 'googleOAuth']);

//
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/fetch', [AuthController::class, 'fetch']);

    // Verified
    Route::middleware(['verified'])->group(function () {
        // Group
        Route::prefix('/group')->group(function () {
            Route::get('/getGroup', [GroupController::class, 'getGroup']);
        });
    });

    // User
    Route::put('/user/edit-profile', [AuthController::class, 'editProfile']);
    Route::middleware(['isadmin'])->group(function () {
        Route::prefix('/user')->group(function () {
            Route::get('/getUserList', [UserController::class, 'getUserList']);
        });
    });
});
