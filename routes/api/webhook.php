<?php

use App\Http\Controllers\v1\Webhook\WebhooksController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/webhooks')->group(function () {
    Route::post('paystack', [WebhooksController::class, 'paystackEvent']);
});
