<?php

namespace Database\Factories\Payment;

use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\Payment\PaymentMode;
use App\Models\Payment\Tax;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxFactory extends Factory
{
    protected $model = Tax::class;

    public function definition(): array
    {
        $rateType = $this->faker->randomElement(['percentage', 'fixed']);
        return [
            'country_code' => 'GH',
            'merchant_id' => $this->faker->randomElement(Merchant::query()->pluck('id')->toArray()),
            'payment_api_id' => $this->faker->randomElement(PaymentApi::query()->pluck('id')->toArray()),
            'payment_mode_id' => $this->faker->randomElement(PaymentMode::query()->pluck('id')->toArray()),
            'name' => $this->faker->word,
            'rate_type' => $rateType,
            'rate_amount' => $rateType === 'percentage' ? $this->faker->randomFloat(1, 0, 100) : $this->faker->randomFloat(2, 1, 500),
            'start_date' => now()->subMonths(random_int(1, 10))->toDateString(),
            'end_date' => now()->addMonths(random_int(1, 110))->toDateString(),
        ];
    }
}
