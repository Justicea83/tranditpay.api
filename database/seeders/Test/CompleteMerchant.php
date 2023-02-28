<?php

namespace Database\Seeders\Test;

use App\Models\Collection\Country;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompleteMerchant extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'justice@tranditpay.com';

        DB::beginTransaction();
        try {
            if (User::query()->where('email', $email)->exists()) {
                return;
            }

            /** @var Country $country */
            $country = Country::query()->where('iso2', 'GH')->first();

            /** @var User $user */
            $user = User::factory()
                ->create([
                    'first_name' => 'Francis',
                    'last_name' => 'Essiet',
                    'email' => $email,
                ]);

            Merchant::factory()
                ->count(3)
                ->create([
                    'country_id' => $country->id,
                    'owner_id' => $user->id,
                ]);

            foreach ($user->merchants as $merchant) {
                PaymentType::factory()->count(250)->create([
                    'merchant_id' => $merchant->id
                ]);
            }

            DB::commit();
        } catch (\Throwable $t) {
            DB::rollBack();
            throw $t;
        }


        /*     for ($i = 0; $i < 3; $i++) {

             }*/
    }
}
