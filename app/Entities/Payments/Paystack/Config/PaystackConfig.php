<?php

namespace App\Entities\Payments\Paystack\Config;

abstract class PaystackConfig
{
    protected string $publicKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected string $url;

    function __construct()
    {
        $this->publicKey = config('paystack.publicKey');
        $this->secretKey = config('paystack.secretKey');
        $this->baseUrl = config('paystack.paymentUrl');
    }
}
