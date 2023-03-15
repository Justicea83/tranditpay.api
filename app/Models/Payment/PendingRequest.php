<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $type
 * @property object $payload
 * @property string $reference
 * @property int $owner_id
 */
class PendingRequest extends Model
{
    use HasFactory;

    protected $fillable = ['reference', 'payload', 'type'];
}
