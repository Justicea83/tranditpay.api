<?php

namespace App\Entities\PendingRequests;

abstract class PendingPaymentAction
{
    public string $provider;
    public float $amount;

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
}
