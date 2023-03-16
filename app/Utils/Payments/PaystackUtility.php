<?php

namespace App\Utils\Payments;

class PaystackUtility
{
    const NAME = 'paystack';
    const CACHE_PREFIX = "__paystack__cache";

    const TRANSACTION_ENDPOINT = '/transaction';
    const BANKS_ENDPOINT = '/bank';
    const CHARGE_ENDPOINT = '/charge';
    const SUBMIT_OTP_ENDPOINT = self::CHARGE_ENDPOINT.'/submit_otp';
    const REVERSAL_ENDPOINT = '/refund';
    const TRANSACTION_TYPE = 'paystack_funding';
    const INIT_TRANSACTION_ENDPOINT = self::TRANSACTION_ENDPOINT . '/initialize';
    const VERIFY_TRANSACTION_ENDPOINT = self::TRANSACTION_ENDPOINT . '/verify/';

    const STATUS_PAY_OFFLINE = "pay_offline";
    const STATUS_SENT_OTP = "sent_otp";
    const STATUS_SUCCESS = "success";
    const STATUS_PENDING = "pending";

    const PENDING_MESSAGE = "Your transaction is being processed";
    const OFFLINE_MESSAGE = "Please enter your PIN";
    const EVENT_CHARGE_SUCCESS = "charge.success";
    const DOMAIN_TEST = "test";

    //payment channels
    const CHANNEL_MOMO = 'mobile_money';
    const CHANNEL_BANK_TRANSFER = 'bank_transfer';
    const CHANNEL_CARD = 'card';
    const CHANNEL_BANK = 'bank';
    const CHANNEL_USSD = 'ussd';
    const CHANNEL_QR = 'qr';
}
