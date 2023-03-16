<?php

namespace App\Utils\Payments;

class FlutterwaveUtility
{
    const NAME = "flutterwave";
    const SUCCESS_RESPONSE = 'success';

    const TRANSACTION_TYPE_CARD = 'CARD_TRANSACTION'; //CARD_TRANSACTION

    const ENDPOINT_CHARGES = 'charges';
    const ENDPOINT_VALIDATE_CHARGE = 'validate-charge';
    const ENDPOINT_BANKS = 'banks';

    const CACHE_PREFIX = "__flw__cache";
    const MOMO_TYPE_GHANA = "mobile_money_ghana";
    const MOMO_TYPE_MPESA = "mpesa";
    const MOMO_TYPE_RWANDA = "mobile_money_rwanda";
    const MOMO_TYPE_ZAMBIA = "mobile_money_zambia";
    const MOMO_TYPE_UGANDA = "mobile_money_uganda";
}
