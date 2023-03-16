<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentApiCharge extends Model
{
    use HasFactory;

    protected $fillable = ['payment_api_id', 'payment_mode_id', 'fixed', 'charge'];
}
