<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentApi;
use App\Utils\Payments\PaystackUtility;
use App\Utils\StatusUtils;
use Illuminate\Database\Seeder;

class PaymentApiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apis = [
            [
                'name' => PaystackUtility::NAME,
                'status' => StatusUtils::ACTIVE
            ],
            [
                'name' => 'flutterwave',
            ],
        ];

        foreach ($apis as $api) {
            /** @var PaymentApi $paymentApi */
            $paymentApi = PaymentApi::query()->firstOrCreate([
                'name' => $api['name']
            ]);

           if(isset($api['status'])){
               $paymentApi->status = $api['status'];
               $paymentApi->save();
           }
        }
    }
}
