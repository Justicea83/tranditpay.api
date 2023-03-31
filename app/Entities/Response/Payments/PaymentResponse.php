<?php

namespace App\Entities\Response\Payments;

class PaymentResponse
{
    public int $code = 0;
    private array $payment_info;
    public array $meta;
    public string $reference;
    public string $provider;
    public string $email;
    public bool $processed = false;
    public string|null $message = null;

    public static function instance() : PaymentResponse{
        return new PaymentResponse();
    }

    /**
     * @param array $payment_info
     * @return PaymentResponse
     */
    public function setPaymentInfo(array $payment_info): PaymentResponse
    {
        $this->payment_info = $payment_info;
        return $this;
    }

    /**
     * @param string $reference
     * @return PaymentResponse
     */
    public function setReference(string $reference): PaymentResponse
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @param string $provider
     * @return PaymentResponse
     */
    public function setProvider(string $provider): PaymentResponse
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @param string $email
     * @return PaymentResponse
     */
    public function setEmail(string $email): PaymentResponse
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param bool $processed
     * @return PaymentResponse
     */
    public function setProcessed(bool $processed): PaymentResponse
    {
        $this->processed = $processed;
        return $this;
    }

    /**
     * @param int $code
     * @return PaymentResponse
     */
    public function setCode(int $code = 0): PaymentResponse
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string|null $message
     * @return PaymentResponse
     */
    public function setMessage(?string $message): PaymentResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function getPaymentInfo(): array
    {
        return $this->payment_info;
    }

    /**
     * @param array $meta
     * @return PaymentResponse
     */
    public function setMeta(array $meta): PaymentResponse
    {
        $this->meta = $meta;
        return $this;
    }
}
