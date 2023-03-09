<?php

namespace App\Models\Merchant;

use App\Models\Collection\Country;
use App\Models\Payment\PaymentType;
use App\Models\Payment\SettlementBank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $owner
 * @property mixed $status
 * @property Country $country
 * @property mixed $name
 * @property mixed $primary_email
 * @property mixed $primary_phone
 */
class Merchant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'owner_id'];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function paymentTypes(): HasMany
    {
        return $this->hasMany(PaymentType::class, 'merchant_id');
    }

    public function settlementBanks(): HasMany{
        return $this->hasMany(SettlementBank::class);
    }

}
