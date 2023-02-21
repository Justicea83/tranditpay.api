<?php

namespace App\Models\Collection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string currency
 * @property string dial_code
 * @property mixed id
 * @property mixed code
 * @property mixed name
 */
class Country extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
