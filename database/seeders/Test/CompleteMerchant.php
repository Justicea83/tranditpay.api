<?php

namespace Database\Seeders\Test;

use App\Models\Collection\Country;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentMode;
use App\Models\Payment\PaymentType;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Utils\Payments\Enums\FundsLocation;
use App\Utils\Payments\Enums\TransactionStatus;
use App\Utils\StatusUtils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Throwable;

class CompleteMerchant extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'justice@tranditpay.com';

        // add a few transactions
        $transaction = Transaction::query()->first();
        for($i = 0; $i < 1; $i++) {
            if($transaction) {
                $amount = random_int(100, 3000);
                /** @var Transaction $newTransaction */
                $newTransaction = $transaction->replicate();
                $newTransaction->amount = $amount;
                $newTransaction->tax_amount = $amount * 0.05;
                $newTransaction->funds_location = FundsLocation::cases()[random_int(0,1)]->value;
                $newTransaction->status = TransactionStatus::cases()[random_int(0,3)]->value;
                $newTransaction->payment_method = PaymentMode::query()->pluck('name')->toArray()[random_int(0,1)];
                $newTransaction->created_at = Carbon::now()->subDays(random_int(0, 120));
                $newTransaction->save();
            }
        }

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
                    'status' => StatusUtils::ACTIVE
                ]);

            foreach ($user->merchants as $merchant) {
                foreach (['School Fees', 'Printing Fees', 'Monthly Tithe', 'Collection'] as $item) {
                    PaymentType::factory()->create([
                        'merchant_id' => $merchant->id,
                        'name' => $item
                    ]);
                }
            }

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }


        /*     for ($i = 0; $i < 3; $i++) {

             }*/
    }
}
