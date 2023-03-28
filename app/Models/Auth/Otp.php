<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $expires_at
 */
class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'otp_code', 'expires_at'];
}
