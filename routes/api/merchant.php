<?php

use App\Http\Controllers\V1\Merchant\MerchantsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/merchants')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('setup', [MerchantsController::class, 'setup']);
    });

    //authenticated routes
    Route::middleware('auth:api')->group(function () {
        Route::get('', [MerchantsController::class, 'getMerchants']);
        Route::get('{id}/payment-types', [MerchantsController::class, 'getPaymentTypes']);
        Route::get('{id}/payment-types/{paymentTypeId}/form', [MerchantsController::class, 'getForm']);
        Route::get('payment-modes', [MerchantsController::class, 'getPaymentModes']);
    });
});
