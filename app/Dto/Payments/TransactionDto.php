<?php

namespace App\Dto\Payments;

use App\Dto\BaseDto;
use App\Models\Payment\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransactionDto extends BaseDto
{
    public string $amount;
    public string $tax_amount;
    public array $user;
    public string $status;
    public string $funds_location;
    public string $payment_method;
    public string $merchant_name;
    public string $currency;
    public string $reference;
    public string $transaction_type;

    /**
     * @param Transaction $model
     * @return TransactionDto
     */
    public function mapFromModel($model): TransactionDto
    {
        if (!$model->id) {
            return self::instance();
        }
        return self::instance()
            ->setId($model->id)
            ->setCurrency($model->currency)
            ->setStatus($model->status)
            ->setFundsLocation($model->funds_location)
            ->setPaymentMethod($model->payment_method)
            ->setReference($model->reference)
            ->setTransactionType($model->model->name)
            ->setMerchantName($model->merchant->name)
            ->setDate(Carbon::parse($model->created_at)->format('M d Y h:i'))
            ->setAmount(sprintf("%s %s", $model->currency, number_format($model->amount, 2)))
            ->setTaxAmount(sprintf("%s %s", $model->currency, number_format($model->tax_amount, 2)));
    }

    private static function instance(): TransactionDto
    {
        return new TransactionDto();
    }

    public static function map(?Transaction $model): ?TransactionDto
    {
        $instance = self::instance();
        if (!$model) {
            return null;
        }
        return $instance->mapFromModel($model);
    }

    /**
     * @param string $status
     * @return TransactionDto
     */
    public function setStatus(string $status): TransactionDto
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $funds_location
     * @return TransactionDto
     */
    public function setFundsLocation(string $funds_location): TransactionDto
    {
        $this->funds_location = $funds_location;
        return $this;
    }

    /**
     * @param string $payment_method
     * @return TransactionDto
     */
    public function setPaymentMethod(string $payment_method): TransactionDto
    {
        $this->payment_method = Str::title(str_replace('_', ' ', $payment_method));
        return $this;
    }

    /**
     * @param string $currency
     * @return TransactionDto
     */
    public function setCurrency(string $currency): TransactionDto
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $reference
     * @return TransactionDto
     */
    public function setReference(string $reference): TransactionDto
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @param string $amount
     * @return TransactionDto
     */
    public function setAmount(string $amount): TransactionDto
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $tax_amount
     * @return TransactionDto
     */
    public function setTaxAmount(string $tax_amount): TransactionDto
    {
        $this->tax_amount = $tax_amount;
        return $this;
    }

    /**
     * @param array $user
     * @return TransactionDto
     */
    public function setUser(array $user): TransactionDto
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $transaction_type
     * @return TransactionDto
     */
    public function setTransactionType(string $transaction_type): TransactionDto
    {
        $this->transaction_type = $transaction_type;
        return $this;
    }

    /**
     * @param string $merchant_name
     * @return TransactionDto
     */
    public function setMerchantName(string $merchant_name): TransactionDto
    {
        $this->merchant_name = $merchant_name;
        return $this;
    }
}
