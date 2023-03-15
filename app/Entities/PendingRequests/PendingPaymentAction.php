<?php

namespace App\Entities\PendingRequests;

abstract class PendingPaymentAction
{
    public string $provider;
    public string $method;
    public string $currency;
    public float $amount;
    public float $taxAmount;
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

    /**
     * @param float $taxAmount
     * @return PendingPaymentAction
     */
    public function setTaxAmount(float $taxAmount): PendingPaymentAction
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    /**
     * @param string $currency
     * @return PendingPaymentAction
     */
    public function setCurrency(string $currency): PendingPaymentAction
    {
        $this->currency = $currency;
        return $this;
    }
}
