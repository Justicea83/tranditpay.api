<?php

namespace App\Services\Payments\Transaction;

use App\Entities\Response\Payments\PaymentResponse;
use App\Models\Payment\PendingRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ITransactionService
{
    public function processPayment(User $user, int $merchantId, array $payload);

    public function createPendingAction(User $user, array $payload): PaymentResponse;

    public function getTransactions(User $user): Paginator;

    public function processPendingRequests();

    public function getTransactionApplicableTax(?User $user, array $payload): array;

    public function processPendingRequest(PendingRequest $pendingRequest);

    public function reimburseMerchants();

}
