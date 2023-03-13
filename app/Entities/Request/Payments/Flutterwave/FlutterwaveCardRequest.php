<?php

namespace App\Entities\Request\Payments\Flutterwave;

class FlutterwaveCardRequest extends FlutterwaveRequest
{
    public string $card_number;
    public string $cvv;
    public string $expiry_month;
    public string $expiry_year;
    public string $authorization;

    public static function instance(): FlutterwaveCardRequest
    {
        return new FlutterwaveCardRequest();
    }

    /**
     * @param string $card_number
     * @return FlutterwaveCardRequest
     */
    public function setCardNumber(string $card_number): FlutterwaveCardRequest
    {
        $this->card_number = $card_number;
        return $this;
    }

    /**
     * @param string $cvv
     * @return FlutterwaveCardRequest
     */
    public function setCvv(string $cvv): FlutterwaveCardRequest
    {
        $this->cvv = $cvv;
        return $this;
    }

    /**
     * @param string $expiry_month
     * @return FlutterwaveCardRequest
     */
    public function setExpiryMonth(string $expiry_month): FlutterwaveCardRequest
    {
        $this->expiry_month = $expiry_month;
        return $this;
    }

    /**
     * @param string $expiry_year
     * @return FlutterwaveCardRequest
     */
    public function setExpiryYear(string $expiry_year): FlutterwaveCardRequest
    {
        $this->expiry_year = $expiry_year;
        return $this;
    }


    /**
     * @param string $authorization
     * @return FlutterwaveCardRequest
     */
    public function setAuthorization(string $authorization): FlutterwaveCardRequest
    {
        $this->authorization = $authorization;
        return $this;
    }
}
