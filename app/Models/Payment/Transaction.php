<?php

namespace App\Models\Payment;

use App\Entities\Payments\Transactions\TransactionMap;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * @property mixed|string $funds_location
 * @property mixed|string $status
 * @property mixed $payment_method
 * @property Carbon|mixed $created_at
 * @property int|mixed $amount
 * @property float|mixed $tax_amount
 * @property mixed $id
 * @property mixed $currency
 * @property mixed $reference
 * @property Merchant $merchant
 * @property mixed $model
 * @property PaymentApi $network
 * @property mixed $merchant_id
 */
class Transaction extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['user_id', 'amount', 'tax_amount', 'merchant_id', 'status', 'funds_location', 'payment_method', 'currency', 'reference', 'model_id', 'model_type', 'network_id'];

    public static function builder(): TransactionMap
    {
        return new TransactionMap();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(PaymentApi::class, 'network_id');
    }
}
