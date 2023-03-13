<?php

namespace App\Services\Payments;

use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\VerifyPaymentResponse;
use App\Models\User;

class PaymentService implements IPaymentService
{

    public function collect(array $data, User $user, $paymentApiId, float $amount = null, string $reference = null): PaymentResponse
    {
        // TODO: Implement collect() method.
    }

    public function verifyTransaction(User $user, string $ref): VerifyPaymentResponse
    {
        // TODO: Implement verifyTransaction() method.
    }

    public function getPaymentModes(User $user): array
    {
        // TODO: Implement getPaymentModes() method.
    }

    public function processPayStackWebhookEvents(array $data): PaymentResponse
    {
        // TODO: Implement processPayStackWebhookEvents() method.
    }

    public function submitOtp(array $data)
    {
        // TODO: Implement submitOtp() method.
    }
}
