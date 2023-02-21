<?php

namespace App\Models\Copilot;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

/**
 * @property mixed $pilot
 * @property Collection $restrictions
 * @property float|int|mixed|string $suspended_until
 * @property bool|mixed $blocked
 */
class Copilot extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id', 'pilot_id'];

    public function pilot(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pilot_id');
    }

    public function restrictions(): HasManyThrough
    {
        return $this->hasManyThrough(Restriction::class, CopilotRestriction::class);
    }

    public static function findByMerchantAndPilot(int $merchantId, int $userId): ?Copilot
    {
        /** @var Copilot $copilot */
        $copilot = self::query()->where('merchant_id', $merchantId)->where('pilot_id', $userId)->first();
        return $copilot;
    }
}
