<?php

namespace Database\Seeders;

use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TestMerchantPaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::query()->chunk(50, function (Collection $merchants) {
                /** @var Merchant $merchant */
                foreach ($merchants as $merchant) {
                    if(count($merchant->paymentTypes) <= 0) {
                        foreach (['School Fees', 'Printing Fees', 'Monthly Tithe', 'Collection'] as $item) {
                            try {
                                PaymentType::query()->create([
                                    'name' => $item,
                                    'merchant_id' => $merchant->id,
                                ]);
                            }catch (\Throwable $t) {
                                continue;
                            }

                        }
                    }
                }
            });
    }
}
