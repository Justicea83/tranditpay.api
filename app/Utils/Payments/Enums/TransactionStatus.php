<?php

namespace App\Utils\Payments\Enums;

use App\Utils\StatusUtils;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';
}
