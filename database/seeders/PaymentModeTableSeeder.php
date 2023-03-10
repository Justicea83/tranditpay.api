<?php

namespace Database\Seeders;

use App\Models\Collection\Country;
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
                'name' => 'card',
                'country_id' => null
            ],
            [
                'name' => 'mobile_money',
                'country_id' => $gh->id
            ],
        ];

        foreach ($payments as $payment) {
            PaymentMode::query()->firstOrCreate($payment);
        }
    }
}
