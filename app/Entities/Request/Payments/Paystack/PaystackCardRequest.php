<?php

namespace App\Entities\Request\Payments\Paystack;

class PaystackCardRequest extends PaystackRequest
{
    public array $subaccount;
    public array $channels = [];

    public static function instance(): PaystackCardRequest
    {
        return new PaystackCardRequest();
    }

    /**
     * @param array $subaccount
     * @return PaystackCardRequest
     */
    public function setSubaccount(array $subaccount): PaystackCardRequest
    {
        $this->subaccount = $subaccount;
        return $this;
    }

    public function addChannel(string $channel): PaystackCardRequest
    {
        $this->channels[] = $channel;
        return $this;
    }

    /**
     * @param array $channels
     * @return PaystackCardRequest
     */
    public function setChannels(array $channels): PaystackCardRequest
    {
        $this->channels = $channels;
        return $this;
    }

}
