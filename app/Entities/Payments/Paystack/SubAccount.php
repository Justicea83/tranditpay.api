<?php

namespace App\Entities\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use Illuminate\Support\Facades\Http;

class SubAccount extends PaystackConfig
{
    public string $business_name;
    public string $settlement_bank;
    public string $account_number;
    public float $percentage_charge;
    public string $description;
    public string $primary_contact_email;
    public string $primary_contact_phone;

    function __construct()
    {
        parent::__construct();
        $this->url = $this->baseUrl . '/subaccount';
    }

    /**
     * @param string $business_name
     * @return SubAccount
     */
    public function setBusinessName(string $business_name): SubAccount
    {
        $this->business_name = $business_name;
        return $this;
    }

    /**
     * @param string $settlement_bank
     * @return SubAccount
     */
    public function setSettlementBank(string $settlement_bank): SubAccount
    {
        $this->settlement_bank = $settlement_bank;
        return $this;
    }

    /**
     * @param string $account_number
     * @return SubAccount
     */
    public function setAccountNumber(string $account_number): SubAccount
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param float $percentage_charge
     * @return SubAccount
     */
    public function setPercentageCharge(float $percentage_charge): SubAccount
    {
        $this->percentage_charge = $percentage_charge;
        return $this;
    }

    /**
     * @param string|null $description
     * @return SubAccount
     */
    public function setDescription(?string $description): SubAccount
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param string|null $primary_contact_email
     * @return SubAccount
     */
    public function setPrimaryContactEmail(?string $primary_contact_email): SubAccount
    {
        $this->primary_contact_email = $primary_contact_email;
        return $this;
    }

    /**
     * @param string|null $primary_contact_phone
     * @return SubAccount
     */
    public function setPrimaryContactPhone(?string $primary_contact_phone): SubAccount
    {
        $this->primary_contact_phone = $primary_contact_phone;
        return $this;
    }

    public function create(): ?object
    {
        $response = Http::withToken($this->secretKey)->post(
            $this->url,
            (array)$this
        );

        if ($response->successful() && $response->json()['status'])
            return (object)$response->json()['data'];

        return null;
    }

    public static function instance(): SubAccount
    {
        return new SubAccount();
    }
}
