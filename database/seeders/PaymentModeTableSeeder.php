<?php

namespace Database\Seeders;

use App\Models\Collection\Country;
use App\Models\Payment\PaymentApi;
use App\Models\Payment\PaymentApiCharge;
use App\Models\Payment\PaymentMode;
use Illuminate\Database\Seeder;

class PaymentModeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Country $gh */
        $gh = Country::query()->where('iso2', 'GH')->first();

        $payments = [
            [
                'model' => [
                    'name' => 'card',
                    'country_id' => null
                ],
                'charges' => [
                    'paystack' => [
                        'fixed' => false,
                        'charge' => 1.95
                    ]
                ]
            ],
            [
                'model' => [
                    'name' => 'mobile_money',
                    'country_id' => $gh->id
                ],
                'charges' => [
                    'paystack' => [
                        'fixed' => true,
                        'charge' => 1
                    ]
                ]
            ],
        ];

        foreach ($payments as $payment) {
            /** @var PaymentMode $paymentMode */
            $paymentMode = PaymentMode::query()->firstOrCreate($payment['model']);

            foreach ($payment['charges'] as $apiName => $chargeInfo) {
                /** @var PaymentApi $paymentApi */
                $paymentApi = PaymentApi::query()->where('name', $apiName)->first();

                $count = PaymentApiCharge::query()->where('payment_api_id', $paymentApi->id)->where('payment_mode_id', $paymentMode->id)->count();
                if ($count) {
                    continue;
                }
                PaymentApiCharge::query()->create(
                    array_merge(
                        $chargeInfo,
                        [
                            'payment_api_id' => $paymentApi->id,
                            'payment_mode_id' => $paymentMode->id,
                        ])
                );
            }
        }
    }
}
