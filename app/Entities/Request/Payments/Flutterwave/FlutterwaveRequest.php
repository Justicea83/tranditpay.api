<?php

namespace App\Entities\Request\Payments\Flutterwave;

class FlutterwaveRequest
{
    public array $subaccounts;
    public string $email;
    public string $tx_ref;
    public string $currency;
    public string $fullname;
    public string $phone_number;
    public float $amount;
    public array $meta = [];


    public function setTransactionType($type): FlutterwaveRequest
    {
        $this->meta['transaction_type'] = $type;
        return $this;
    }

    /**
     * @param array $subaccounts
     * @return FlutterwaveRequest
     */
    public function setSubaccounts(array $subaccounts): FlutterwaveRequest
    {
        $this->subaccounts = $subaccounts;
        return $this;
    }

    /**
     * @param string $email
     * @return FlutterwaveRequest
     */
    public function setEmail(string $email): FlutterwaveRequest
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $tx_ref
     * @return FlutterwaveRequest
     */
    public function setTxRef(string $tx_ref): FlutterwaveRequest
    {
        $this->tx_ref = $tx_ref;
        return $this;
    }

    /**
     * @param string $currency
     * @return FlutterwaveRequest
     */
    public function setCurrency(string $currency): FlutterwaveRequest
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $phone_number
     * @return FlutterwaveRequest
     */
    public function setPhoneNumber(string $phone_number): FlutterwaveRequest
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    /**
     * @param float $amount
     * @return FlutterwaveRequest
     */
    public function setAmount(float $amount): FlutterwaveRequest
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $fullName
     * @return FlutterwaveRequest
     */
    public function setFullName(string $fullName): FlutterwaveRequest
    {
        $this->fullname = $fullName;
        return $this;
    }
}
