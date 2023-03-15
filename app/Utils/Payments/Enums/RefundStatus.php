<?php

namespace App\Utils\Payments\Enums;

use App\Utils\StatusUtils;

enum RefundStatus : string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
}
