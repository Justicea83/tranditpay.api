<?php

namespace App\Services\Payments;

use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\VerifyPaymentResponse;
use App\Models\User;

interface IPaymentService
{
    public function collect(array $data, User $user, $paymentApiId, float $amount = null, string $reference = null): PaymentResponse;

    public function verifyTransaction(User $user, string $ref): VerifyPaymentResponse;

    public function getPaymentModes(User $user): array;

    public function processPayStackWebhookEvents(array $data): PaymentResponse;

    public function submitOtp(array $data);
}
