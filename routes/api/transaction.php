<?php

use App\Http\Controllers\v1\Transaction\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/transactions')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('applicable-tax', [TransactionsController::class, 'getTransactionApplicableTax']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('', [TransactionsController::class, 'getTransactions']);
        Route::post('pending', [TransactionsController::class, 'createPendingAction']);
    });
});
