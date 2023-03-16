<?php

namespace App\Models\Payment;

use App\Utils\StatusUtils;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $status
 * @property string $name
 * @property int $id
 */
class PaymentApi extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function scopeActive(Builder $query): void
    {
        $query->where('status', StatusUtils::ACTIVE);
    }
}
