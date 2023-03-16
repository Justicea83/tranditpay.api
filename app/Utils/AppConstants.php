<?php

namespace App\Utils;

class AppConstants
{
    //payment modes
    const APP_PAYMENT_MODE_CARD = 'card';
    const APP_PAYMENT_MODE_BANK = 'bank';
    const APP_PAYMENT_MODE_MOMO = 'mobile_money';

    const USER_STATUS_ACTIVE = 'active';
    const USER_STATUS_SUSPENDED = 'suspended';
    const USER_STATUS_DELETE_REQUESTED = 'delete_requested';

    const USER_STATUSES = [
        self::USER_STATUS_ACTIVE,
        self::USER_STATUS_DELETE_REQUESTED,
        self::USER_STATUS_SUSPENDED
    ];
}
