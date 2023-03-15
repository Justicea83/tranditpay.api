<?php

namespace App\Services\Payments\Transaction;

use App\Entities\Response\Payments\PaymentResponse;
use App\Models\User;

interface ITransactionService
{
    public function processPayment(User $user, int $merchantId, array $payload);

    public function createPendingAction(User $user, array $payload): PaymentResponse;
}
