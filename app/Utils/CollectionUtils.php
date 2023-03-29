<?php

namespace App\Utils;

class CollectionUtils
{
    const COLLECTION_TYPE_CATEGORY = 'categories';
    const COLLECTION_TYPE_COUNTRIES = 'countries';
    const COLLECTION_TYPE_MERCHANTS = 'merchants';
    const COLLECTION_TYPE_MERCHANTS_PAYMENT_TYPES = 'merchants.payment_types';
    const COLLECTION_TYPE_LOCATION = 'location';
    const COLLECTION_TYPE_COUNTRIES_STATES = 'countries.states';

    const COLLECTION_TYPES = [
        self::COLLECTION_TYPE_COUNTRIES_STATES,
        self::COLLECTION_TYPE_COUNTRIES,
        self::COLLECTION_TYPE_LOCATION,
        self::COLLECTION_TYPE_MERCHANTS,
        self::COLLECTION_TYPE_MERCHANTS_PAYMENT_TYPES,
    ];
}
