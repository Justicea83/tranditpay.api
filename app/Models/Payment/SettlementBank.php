<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
