<?php

namespace App\Entities\Payments\Flutterwave\Config;

abstract class FlutterwaveConfig
{
    protected string $publicKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected string $url;
    protected string $encryptionKey;

    function __construct()
    {
        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->baseUrl = config('flutterwave.paymentUrl');
        $this->encryptionKey = config('flutterwave.encryptionKey');
    }
}
