<?php

namespace App\Services\Webhook;

use App\Models\Payment\PendingRequest;
use App\Services\Payments\Transaction\ITransactionService;
use App\Utils\Payments\PaystackUtility;
use App\Utils\StatusUtils;

class WebhookService implements IWebhookService
{
    public function __construct(private readonly PendingRequest $pendingRequest, private readonly ITransactionService $transactionService)
    {
    }

    public function paystackEvent(array $payload): void
    {
        [
            'event' => $event,
            'data' => $data
        ] = $payload;
        if ($event === PaystackUtility::EVENT_CHARGE_SUCCESS) {
            /** @var PendingRequest $pendingRequest */
            $pendingRequest = $this->pendingRequest
                ->query()
                ->where('status', StatusUtils::PENDING)
                ->where('reference', $data['reference'])->first();

            $this->transactionService->processPendingRequest($pendingRequest);
        }

    }

    public function flutterwaveEvent(array $payload): void
    {
        // TODO: Implement flutterwaveEvent() method.
    }
}
