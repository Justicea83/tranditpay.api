<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 */
class SettlementMode extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
