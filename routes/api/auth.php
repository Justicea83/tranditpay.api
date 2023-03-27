<?php


use App\Http\Controllers\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->group(function (){
    Route::post('mobile-login',[AuthController::class,'mobileLogin']);
    Route::post('forgot-password',[AuthController::class,'forgotPassword']);
    Route::post('reset-password',[AuthController::class,'resetPassword']);
    Route::post('login-with-refresh-token',[AuthController::class,'refreshToken']);
    Route::post('send-otp',[AuthController::class,'sendOtp'])->middleware('throttle:otp');
    Route::post('verify-otp',[AuthController::class,'verifyOtp']);
    Route::post('login-with-otp',[AuthController::class,'loginWithOtp']);
    //
    Route::middleware('auth:api')->group(function () {
        Route::post('mobile-logout',[AuthController::class,'mobileLogout']);
        Route::get('me',[AuthController::class,'getAuthenticatedUser']);
        Route::post('logout-of-all-devices',[AuthController::class,'logoutOfAllDevices']);
        Route::post('change-password',[AuthController::class,'changePassword']);
    });
});
