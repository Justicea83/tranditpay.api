<?php

namespace App\Entities\Response\Payments\Contracts;

use App\Entities\Response\Payments\PaymentResponse;
use App\Models\User;

interface ShouldTransform
{
    public function transformToPaymentResponse(User $user, bool $processed = false) : PaymentResponse;
}
