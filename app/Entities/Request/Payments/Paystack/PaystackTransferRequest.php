<?php

namespace App\Entities\Request\Payments\Paystack;

class PaystackTransferRequest extends PaystackRequest
{
    public string $source;
    public string $recipient;

    /**
     * @param string $source
     * @return PaystackTransferRequest
     */
    public function setSource(string $source): PaystackTransferRequest
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param string $recipient
     * @return PaystackTransferRequest
     */
    public function setRecipient(string $recipient): PaystackTransferRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    public static function instance(): PaystackTransferRequest
    {
        return new PaystackTransferRequest();
    }

}
