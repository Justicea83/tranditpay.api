<?php

namespace App\Services\Payments;

use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\VerifyPaymentResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\User;
use Illuminate\Support\Collection;

interface IPaymentService
{
    public function collect(array $data, User $user, PaymentApi $paymentApi, Merchant $merchant, ?float $amount = null, ?string $reference = null): PaymentResponse;

    public function verifyTransaction(string $provider, string $ref): VerifyPaymentResponse;

    public function submitOtp(array $data);

    public function getMomoProviders(int $merchantId): Collection;

    public function settlePaystackTransfer(string $event, array $data);

}
