<?php

namespace App\Models\Payment;

use App\Models\Form\Form;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

/**
 * @property mixed $id
 * @property mixed $name
 * @property Form $form
 */
class PaymentType extends Model
{
    use HasFactory, Searchable;

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function form(): HasOne
    {
        return $this->hasOne(Form::class);
    }
}
