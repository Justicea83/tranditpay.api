<?php

namespace App\Utils;

class RestrictionUtils
{
    public const RESTRICTION_FULL_ACCESS = 'full_access';
    public const RESTRICTION_WEB = 'web';
    public const RESTRICTION_MOBILE = 'mobile';

    public const public = [
        self::RESTRICTION_FULL_ACCESS,
        self::RESTRICTION_WEB,
        self::RESTRICTION_MOBILE,
    ];
}
