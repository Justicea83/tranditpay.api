<?php

namespace Database\Seeders;

use App\Models\Payment\SettlementMode;
use Illuminate\Database\Seeder;

class SettlementModeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modes = [
            [
                'name' => 'bank',
            ],
            [
                'name' => 'mobile_money',
            ],
        ];

        foreach ($modes as $mode) {
            SettlementMode::query()->firstOrCreate($mode);
        }
    }
}
