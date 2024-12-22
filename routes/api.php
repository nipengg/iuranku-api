<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupApplicationController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\GroupMemberController;
use App\Http\Controllers\API\GroupNewsController;
use App\Http\Controllers\API\GroupTuitionSettingController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\RequestTuitionController;
use App\Http\Controllers\API\TuitionController;
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

    Route::prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'getNews']);
    });


    // Verified
    Route::middleware(['verified'])->group(function () {
        // Group
        Route::prefix('/group')->group(function () {
            Route::get('/getGroup', [GroupController::class, 'getGroup']);

            Route::prefix('/news')->group(function () {
                Route::get('/', [GroupNewsController::class, 'getGroupNews']);
                Route::get('/detail', [GroupNewsController::class, 'getGroupNewsById']);
                Route::post('/store', [GroupNewsController::class, 'insertGroupNews']);
                Route::post('/update', [GroupNewsController::class, 'updateGroupNews']);
                Route::post('/delete', [GroupNewsController::class, 'deleteGroupNews']);
            });

            Route::prefix('/members')->group(function () {
                Route::get('/', [GroupMemberController::class, 'getGroupMembers']);
                Route::get('/find-member', [GroupMemberController::class, 'findMembers']);
                Route::post('/leave', [GroupMemberController::class, 'leaveGroup']);
            });

            Route::prefix('/application')->group(function () {
                Route::get('/', [GroupApplicationController::class, 'getGroupApplication']);
                Route::post('/handle', [GroupApplicationController::class, 'handleGroupApplicationResponse']);
                Route::post('/invite', [GroupApplicationController::class, 'inviteUserGroupApplication']);
            });

            Route::prefix('/tuition-setting')->group(function () {
                Route::get('/', [GroupTuitionSettingController::class, 'getGroupTuitionSetting']);
                Route::post('/update', [GroupTuitionSettingController::class, 'insertOrUpdateGroupTuitionSetting']);
            });

            Route::prefix('/request-tuition')->group(function () {
                Route::get('/', [RequestTuitionController::class, 'getRequestTuition']);
                Route::get('/id', [RequestTuitionController::class, 'getRequestTuitionById']);
                Route::post('/store', [RequestTuitionController::class, 'storeRequestTuition']);
                Route::post('/handle', [RequestTuitionController::class, 'handleRequestTuition']);
            });

            Route::prefix('/tuition')->group(function () {
                Route::get('/member', [TuitionController::class, 'getTuitionByMemberId']);
                Route::get('/member/payment', [TuitionController::class, 'getTuitionPaymentMember']);
                Route::get('/member/status', [TuitionController::class, 'getTuitionMember']);
                Route::get('/member/detail', [TuitionController::class, 'getTuitionMemberDetail']);
                Route::post('/store', [TuitionController::class, 'storeTuition']);
            });
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
