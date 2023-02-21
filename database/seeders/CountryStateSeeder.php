<?php

namespace Database\Seeders;

use App\Models\Collection\Country;
use Illuminate\Database\Seeder;

class CountryStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Country::query()->count()) {
            return;
        }

        $countryStates = json_decode(file_get_contents(public_path('files/countries+states.json')), true);
        foreach ($countryStates as $countryState) {

            /** @var Country $country */
            $country = Country::query()->firstOrCreate([
                'name' => $countryState['name'],
                'iso2' => $countryState['iso2'],
                'iso3' => $countryState['iso3'],
                'code' => $countryState['phone_code'],
                'currency' => $countryState['currency'],
                'currency_name' => $countryState['currency_name'],
                'currency_symbol' => $countryState['currency_symbol'],
                'phone_code' => $countryState['phone_code'],
                'lat' => $countryState['latitude'],
                'lng' => $countryState['longitude'],
            ]);

            foreach ($countryState['states'] as $state) {
                $country->states()->firstOrCreate([
                    'lat' => $state['latitude'],
                    'lng' => $state['longitude'],
                    'name' => $state['name'],
                    'type' => $state['type'],
                    'state_code' => $state['state_code']
                ]);
            }


        }

    }
}
