<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $status
 */
class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'transaction_id', 'status', 'merchant_id'
    ];
}
