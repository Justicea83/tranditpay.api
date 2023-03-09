<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $status
 */
class PaymentApi extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
