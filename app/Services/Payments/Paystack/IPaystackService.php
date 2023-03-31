<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Request\Payments\Paystack\PaystackCardRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Request\Payments\Paystack\PaystackReceiptRequest;
use App\Entities\Request\Payments\Paystack\PaystackTransferRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\User;

interface IPaystackService
{
    public function initializePayment(PaystackCardRequest $request): PaystackResponse;

    public function momoPay(User $user, PaystackMomoRequest $request): ?PaystackResponse;

    public function verifyTransaction(string $ref): bool;

    public function createTransferReceipt(PaystackReceiptRequest $payload): PaystackResponse;

    public function singleTransfer(PaystackTransferRequest $payload): PaystackResponse;

}
