<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('loginPage');
    });
    Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/admin')->middleware(['isadmin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('/user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.user.create');
            Route::get('/{ids}', [UserController::class, 'detail'])->name('admin.user.detail');
            Route::post('/update/{ids}', [UserController::class, 'update'])->name('admin.user.update');
            Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
            Route::post('/import', [UserController::class, 'importExcel'])->name('admin.import.excel');
        });

        Route::prefix('/group')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('admin.group.index');
            Route::get('/create', [GroupController::class, 'create'])->name('admin.group.create');
            Route::get('/{ids}', [GroupController::class, 'detail'])->name('admin.group.detail');
            Route::post('/update/{ids}', [GroupController::class, 'update'])->name('admin.group.update');
            Route::post('/store', [GroupController::class, 'store'])->name('admin.group.store');
        });

        Route::prefix('/news')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('admin.news.index');
            Route::get('/create', [NewsController::class, 'create'])->name('admin.news.create');
            Route::get('/{ids}', [NewsController::class, 'detail'])->name('admin.news.detail');
            Route::post('/store', [NewsController::class, 'store'])->name('admin.news.store');
        });
    });
});
