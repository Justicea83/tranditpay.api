<?php

namespace App\Utils\Payments\Enums;

enum FundsLocation: string
{
    case Application = 'application';
    case Merchant = 'merchant';
}
