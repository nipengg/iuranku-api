<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('loginPage');
    });
    Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('/user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/{ids}', [UserController::class, 'detail'])->name('admin.user.detail');
            Route::post('/import', [UserController::class, 'importExcel'])->name('admin.import.excel');
        });
    });

});
