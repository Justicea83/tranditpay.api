<?php

namespace App\Services\Payments\Transaction;

use App\Entities\Response\Payments\PaymentResponse;
use App\Models\User;
use Illuminate\Support\Collection;

interface ITransactionService
{
    public function processPayment(User $user, int $merchantId, array $payload);

    public function createPendingAction(User $user, array $payload): PaymentResponse;

    public function processPendingRequests();

    public function getTransactionApplicableTax(User $user, array $payload): array;
}
