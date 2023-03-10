<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Test\CompleteMerchant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CountryStateSeeder::class);
        $this->call(RestrictionsTableSeeder::class);

        $this->call(FormFieldTypesTableSeeder::class);
        $this->call(PaymentApiTableSeeder::class);
        $this->call(SettlementModeTableSeeder::class);
        $this->call(PaymentModeTableSeeder::class);

        if (app()->environment(['local'])) {
            $this->call(CompleteMerchant::class);
        }


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
