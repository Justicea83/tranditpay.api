<?php

namespace Database\Factories\Merchant;

use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Utils\MerchantUtils;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    protected $model = Merchant::class;

    public function definition(): array
    {
        $owners = User::query()->pluck('id')->toArray();
        return [
            'name' => $this->faker->company,
            'primary_email' => $this->faker->companyEmail,
            'primary_phone' => $this->faker->phoneNumber,
            'primary_email_verified_at' => now(),
            'primary_phone_verified_at' => now(),
            'website' => $this->faker->url,
            'about' => $this->faker->sentence(80),
            'address' => $this->faker->address,
            'avatar' => $this->faker->imageUrl(),
            'country_id' => 84,
            'owner_id' => $this->faker->randomElement($owners),
            'status' => $this->faker->randomElement(MerchantUtils::MERCHANT_STATUSES)
        ];
    }
}
