<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Request\Payments\Paystack\PaystackCardRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\User;

interface IPaystackService
{
    public function initializePayment(PaystackCardRequest $request): PaystackResponse;

    public function momoPay(User $user, PaystackMomoRequest $request): ?PaystackResponse;
}
