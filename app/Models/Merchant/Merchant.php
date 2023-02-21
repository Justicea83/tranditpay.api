<?php

namespace App\Models\Merchant;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $owner
 */
class Merchant extends Model
{
    use HasFactory;

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

}
