<?php

namespace App\Utils;

use App\Models\User;

class CacheKeys
{
    const USER_LOCATION = 'USER_LOCATION';

    public static function getKeyForUser(User $user, string $key): string
    {
        return $user->id . '_' . constant('App\Utils\CacheKeys::' . $key);
    }
}
