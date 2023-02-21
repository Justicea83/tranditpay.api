<?php

namespace Database\Seeders;

use App\Models\Copilot\Restriction;
use App\Utils\RestrictionUtils;
use Illuminate\Database\Seeder;

class RestrictionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Restriction::query()->count()) {
            return;
        }

        foreach (RestrictionUtils::public as $restriction) {
            Restriction::query()->firstOrCreate([
                'name' => $restriction
            ]);
        }
    }
}
