<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Request\Payments\Paystack\PaystackCardRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\User;

interface IPaystackService
{
    public function initializePayment(PaystackCardRequest $request): PaystackResponse;

    public function buildCashbackCardPayload(User $user, array $data, float $amount, string $ref, array $channels = []): PaystackCardRequest;
}
