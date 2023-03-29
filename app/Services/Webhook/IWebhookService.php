<?php

namespace App\Services\Webhook;

interface IWebhookService
{
    public function paystackEvent(array $payload): void;

    public function flutterwaveEvent(array $payload): void;
}
