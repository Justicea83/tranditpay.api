<?php

namespace App\Entities\PendingRequests;

abstract class PendingPaymentAction
{
    public string $provider;
    public string $method;
    public float $amount;
    public int $merchantId;

    /**
     * @param string $provider
     * @return PendingPaymentAction
     */
    public function setProvider(string $provider): PendingPaymentAction
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @param float $amount
     * @return PendingPaymentAction
     */
    public function setAmount(float $amount): PendingPaymentAction
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param int $merchantId
     * @return PendingPaymentAction
     */
    public function setMerchantId(int $merchantId): PendingPaymentAction
    {
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @param string $method
     * @return PendingPaymentAction
     */
    public function setMethod(string $method): PendingPaymentAction
    {
        $this->method = $method;
        return $this;
    }
}
