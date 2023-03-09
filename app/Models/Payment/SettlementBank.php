<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $account_number
 * @property mixed $bank_name
 * @property array $extra_data
 */
class SettlementBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'settlement_mode_id',
        'extra_data',
        'bank_name',
        'account_name',
        'account_number',
    ];
}
