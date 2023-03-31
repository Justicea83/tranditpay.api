<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

/**
 * @property mixed $account_number
 * @property mixed $bank_name
 * @property string $extra_data
 * @property Bank $bank
 */
class SettlementBank extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'merchant_id',
        'settlement_mode_id',
        'extra_data',
        'bank_name',
        'account_name',
        'account_number',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
