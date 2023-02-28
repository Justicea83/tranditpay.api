<?php

namespace Database\Factories\Payment;

use App\Models\Payment\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;


class PaymentTypeFactory extends Factory
{
    protected $model = PaymentType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(4, true)
        ];
    }
}
