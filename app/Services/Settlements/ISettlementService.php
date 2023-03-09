<?php

namespace App\Services\Settlements;

use App\Models\Merchant\Merchant;

interface ISettlementService
{
    public function addMerchantSubAccounts(Merchant $merchant);
}
