<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $extra_info
 */
class Bank extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'country_id'];
}
