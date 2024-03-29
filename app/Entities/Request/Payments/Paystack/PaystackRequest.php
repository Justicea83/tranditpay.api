<?php

namespace App\Entities\Request\Payments\Paystack;

class PaystackRequest
{
    public float $amount;
    public string $email;
    public string $currency;
    public string $reference;
    public array $metadata;
    public float $transaction_charge;
    public string $reason;

    /**
     * The amount should be in pesewas
     * @param mixed $amount
     * @return PaystackRequest
     */
    public function setAmount(float $amount): PaystackRequest
    {
        $this->amount = $amount * 100;
        return $this;
    }

    public function setTransactionType($type): PaystackRequest
    {
        $this->metadata['transaction_type'] = $type;
        return $this;
    }

    /**
     * @param string $email
     * @return PaystackRequest
     */
    public function setEmail(string $email): PaystackRequest
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param float $transaction_charge
     * @return PaystackRequest
     */
    public function setTransactionCharge(float $transaction_charge): PaystackRequest
    {
        $this->transaction_charge = $transaction_charge;
        return $this;
    }

    /**
     * @param array $metadata
     * @return PaystackRequest
     */
    public function setMetadata(array $metadata): PaystackRequest
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @param string $reference
     * @return PaystackRequest
     */
    public function setReference(string $reference): PaystackRequest
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @param string $currency
     * @return PaystackRequest
     */
    public function setCurrency(string $currency): PaystackRequest
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $reason
     * @return PaystackRequest
     */
    public function setReason(string $reason): PaystackRequest
    {
        $this->reason = $reason;
        return $this;
    }
}
