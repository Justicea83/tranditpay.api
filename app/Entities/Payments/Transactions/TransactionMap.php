<?php

namespace App\Entities\Payments\Transactions;

use App\Models\Payment\Transaction;

class TransactionMap
{
    public float $amount;
    public float $tax_amount;
    public int $user_id;
    public int $merchant_id;
    public string $status;
    public string $funds_location;
    public string $payment_method;
    public string $currency;
    public string $reference;
    public ?int $model_type;
    public ?string $model_id;

    /**
     * @param float $amount
     * @return TransactionMap
     */
    public function setAmount(float $amount): TransactionMap
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param float $tax_amount
     * @return TransactionMap
     */
    public function setTaxAmount(float $tax_amount): TransactionMap
    {
        $this->tax_amount = $tax_amount;
        return $this;
    }

    /**
     * @param int $user_id
     * @return TransactionMap
     */
    public function setUserId(int $user_id): TransactionMap
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @param int $merchant_id
     * @return TransactionMap
     */
    public function setMerchantId(int $merchant_id): TransactionMap
    {
        $this->merchant_id = $merchant_id;
        return $this;
    }

    /**
     * @param string $status
     * @return TransactionMap
     */
    public function setStatus(string $status): TransactionMap
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $funds_location
     * @return TransactionMap
     */
    public function setFundsLocation(string $funds_location): TransactionMap
    {
        $this->funds_location = $funds_location;
        return $this;
    }

    /**
     * @param string $payment_method
     * @return TransactionMap
     */
    public function setPaymentMethod(string $payment_method): TransactionMap
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    /**
     * @param string $currency
     * @return TransactionMap
     */
    public function setCurrency(string $currency): TransactionMap
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $reference
     * @return TransactionMap
     */
    public function setReference(string $reference): TransactionMap
    {
        $this->reference = $reference;
        return $this;
    }

    public static function builder(): TransactionMap
    {
        return new TransactionMap();
    }

    public function create(): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::query()->create((array)$this);
        return $transaction;
    }

    /**
     * @param int|null $model_type
     * @return TransactionMap
     */
    public function setModelType(?int $model_type): TransactionMap
    {
        $this->model_type = $model_type;
        return $this;
    }

    /**
     * @param string|null $model_id
     * @return TransactionMap
     */
    public function setModelId(?string $model_id): TransactionMap
    {
        $this->model_id = $model_id;
        return $this;
    }
}
