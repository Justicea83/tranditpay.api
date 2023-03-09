<?php

namespace App\Utils;

class AppUtils
{
    public static function removeSpacesSpecialChar($str): array|string|null
    {
        return preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
    }
}
