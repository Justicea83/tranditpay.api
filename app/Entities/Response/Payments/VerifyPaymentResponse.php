<?php

namespace App\Entities\Response\Payments;

class VerifyPaymentResponse
{
    public int $code = 0;
    public string $reference;
    public string $message;
}
