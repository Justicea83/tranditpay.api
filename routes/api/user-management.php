<?php

use App\Http\Controllers\v1\UserManagement\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user-mgmt')->group(function () {
    Route::middleware('auth:api')->group(function () {

        Route::prefix('users')->group(function () {
            Route::post('', [UserManagementController::class, 'createUser']);
            Route::put('', [UserManagementController::class, 'updateUser']);
            Route::patch('suspend', [UserManagementController::class, 'suspendUser']);
            Route::get('{searchValue}',[UserManagementController::class,'getUserByEmailOrPhone']);
        });

        Route::prefix('copilots')->group(function () {
            Route::post('{merchantId}', [UserManagementController::class, 'addCopilot']);
            Route::delete('', [UserManagementController::class, 'removeCopilot']);
            Route::patch('block', [UserManagementController::class, 'blockCopilot']);
            Route::patch('unblock', [UserManagementController::class, 'unBlockCopilot']);
            Route::patch('suspend', [UserManagementController::class, 'suspendCopilot']);
            Route::patch('unsuspend', [UserManagementController::class, 'unSuspendCopilot']);
            Route::get('{merchantId}',[UserManagementController::class,'getCopilots']);

        });
    });
});
