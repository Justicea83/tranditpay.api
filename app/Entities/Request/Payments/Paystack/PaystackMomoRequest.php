<?php

namespace App\Entities\Request\Payments\Paystack;

class PaystackMomoRequest extends PaystackRequest
{
    public string $provider;
    public string $phone;
    public array $mobile_money = [];

    /**
     * @param string $provider
     * @return PaystackMomoRequest
     */
    public function setProvider(string $provider): PaystackMomoRequest
    {
        $this->mobile_money['provider'] = $provider;
        return $this;
    }

    /**
     * @param string $phone
     * @return PaystackMomoRequest
     */
    public function setPhone(string $phone): PaystackMomoRequest
    {
        $this->mobile_money['phone'] = $phone;
        return $this;
    }

    public static function instance(): PaystackMomoRequest
    {
        return new PaystackMomoRequest();
    }
}
