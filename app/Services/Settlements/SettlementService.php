<?php

namespace App\Services\Settlements;

use App\Entities\Payments\Paystack\Bank;
use App\Entities\Payments\Paystack\SubAccount;
use App\Models\Merchant\Merchant;
use App\Models\Payment\SettlementBank;
use App\Models\Payment\SettlementMode;
use App\Utils\AppUtils;
use App\Utils\Payments\PaystackUtility;
use App\Utils\StatusUtils;

class SettlementService implements ISettlementService
{
    private SettlementMode $settlementMode;

    public function __construct(SettlementMode $settlementMode)
    {
        $this->settlementMode = $settlementMode;
    }

    public function addMerchantSubAccounts(Merchant $merchant)
    {
        if ($merchant->status != StatusUtils::ACTIVE) {
            return;
        }
        /** @var SettlementMode $bankSettlementMode */
        $bankSettlementMode = $this->settlementMode->query()->where('name', 'bank')->first();

        /** @var SettlementBank $settlementBank */
        $settlementBank = $merchant->settlementBanks()->where('settlement_mode_id', $bankSettlementMode->id)->first();
        if (!$settlementBank) {
            return;
        }
        $this->paystackAddMerchantSubAccount($merchant, $settlementBank);
    }

    private function paystackAddMerchantSubAccount(Merchant $merchant, SettlementBank $settlementBank)
    {
        if (isset($settlementBank->extra_data['payment_info']) && isset($settlementBank->extra_data['payment_info'][PaystackUtility::NAME])) return;
        $countryName = $merchant->country->name;
        $accountNo = $settlementBank->account_number;
        $bankName = $settlementBank->bank_name;

        if (!app()->environment('production')) {
            $accountNo = '08100000000';
            $countryName = 'ghana';
            $bankName = 'Access Bank';
        }

        $data = Bank::instance()->fetchAll($countryName);

        $bank = collect($data)->first(function ($bank) use ($bankName) {
            return similar_text($bank['name'], $bankName) >= strlen(AppUtils::removeSpacesSpecialChar($bankName));
        });

        ['code' => $code] = $bank;

        $extraData = $settlementBank->extra_data;

        if (!is_array($extraData)) {
            $extraData = json_decode($extraData, true);
        }

        if (isset($extraData['payment_info']['paystack'])) return;

        $subaccount = SubAccount::instance()
            ->setSettlementBank($code)
            ->setAccountNumber($accountNo)
            ->setBusinessName($merchant->name)
            ->setPrimaryContactEmail($merchant->primary_email)
            ->setPrimaryContactPhone($merchant->primary_phone)
            ->setPercentageCharge(0)
            ->create();

        if ($subaccount == null) return;

        $paystackInfo = [
            'sub_account_id' => $subaccount->subaccount_code,
            'id' => $subaccount->id,
            'bank_name' => $subaccount->settlement_bank
        ];

        $extraData['payment_info'][PaystackUtility::NAME] = $paystackInfo;

        $settlementBank->extra_data = $extraData;
        $settlementBank->save();

    }
}
