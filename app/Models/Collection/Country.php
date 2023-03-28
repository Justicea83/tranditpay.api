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
 * @property mixed $iso2
 */
class Country extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public static function findByISO2(string $iso2): ?Country
    {
        /** @var Country $country */
        $country = self::query()->where('iso2', $iso2)->first();
        return $country;
    }
}
