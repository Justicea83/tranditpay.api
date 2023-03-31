<?php

namespace App\Entities\Request\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;

class PaystackReceiptRequest extends PaystackConfig
{
    public string $type;
    public string $name;
    public string $account_number;
    public string $bank_code;
    public string $currency;

    /**
     * @param string $type
     * @return PaystackReceiptRequest
     */
    public function setType(string $type): PaystackReceiptRequest
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $name
     * @return PaystackReceiptRequest
     */
    public function setName(string $name): PaystackReceiptRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $account_number
     * @return PaystackReceiptRequest
     */
    public function setAccountNumber(string $account_number): PaystackReceiptRequest
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param string $bank_code
     * @return PaystackReceiptRequest
     */
    public function setBankCode(string $bank_code): PaystackReceiptRequest
    {
        $this->bank_code = $bank_code;
        return $this;
    }

    /**
     * @param string $currency
     * @return PaystackReceiptRequest
     */
    public function setCurrency(string $currency): PaystackReceiptRequest
    {
        $this->currency = $currency;
        return $this;
    }

    public static function instance(): PaystackReceiptRequest
    {
        return new PaystackReceiptRequest();
    }
}
