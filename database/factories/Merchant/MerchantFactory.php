<?php

namespace Database\Factories\Merchant;

use App\Models\Merchant\Merchant;
use App\Utils\MerchantUtils;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'primary_email' => $this->faker->companyEmail,
            'primary_phone' => $this->faker->phoneNumber,
            'status' => MerchantUtils::MERCHANT_STATUS_ACTIVE,
            'primary_email_verified_at' => now(),
            'primary_phone_verified_at' => now(),
        ];
    }
}
