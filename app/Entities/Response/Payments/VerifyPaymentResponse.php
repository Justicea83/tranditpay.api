<?php

namespace App\Entities\Response\Payments;

class VerifyPaymentResponse
{
    public bool $valid = false;
    public string $reference;
    public string $message;

    public static function instance(): VerifyPaymentResponse
    {
        return new VerifyPaymentResponse();
    }

    /**
     * @param bool $valid
     * @return VerifyPaymentResponse
     */
    public function setValid(bool $valid): VerifyPaymentResponse
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @param string $reference
     * @return VerifyPaymentResponse
     */
    public function setReference(string $reference): VerifyPaymentResponse
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @param string $message
     * @return VerifyPaymentResponse
     */
    public function setMessage(string $message): VerifyPaymentResponse
    {
        $this->message = $message;
        return $this;
    }
}
