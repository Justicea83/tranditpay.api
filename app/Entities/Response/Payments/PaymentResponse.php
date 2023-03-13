<?php

namespace App\Entities\Response\Payments;

class PaymentResponse
{
    public int $code = 0;
    public array $payment_info;
    public string $reference;
    public string $provider;
    public string $email;
    public bool $process = false;
    public string|null $message = null;
    public array $data;

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
     * @param bool $process
     * @return PaymentResponse
     */
    public function setProcess(bool $process): PaymentResponse
    {
        $this->process = $process;
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
     * @param array $data
     * @return PaymentResponse
     */
    public function setData(array $data): PaymentResponse
    {
        $this->data = $data;
        return $this;
    }
}
