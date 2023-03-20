<?php

namespace App\Models\Payment;

use App\Entities\Payments\Transactions\TransactionMap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $funds_location
 * @property mixed|string $status
 * @property mixed $payment_method
 * @property Carbon|mixed $created_at
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'tax_amount', 'merchant_id', 'status', 'funds_location', 'payment_method', 'currency', 'reference'];

    public static function builder(): TransactionMap
    {
        return new TransactionMap();
    }
}
