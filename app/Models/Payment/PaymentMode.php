<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $name
 */
class PaymentMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'country_id'
    ];
}
