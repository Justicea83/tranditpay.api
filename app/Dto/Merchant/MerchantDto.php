<?php

namespace App\Dto\Merchant;

use App\Dto\BaseDto;
use App\Models\Merchant\Merchant;

class MerchantDto extends BaseDto
{
    public ?string $name;
    public int $id;
    public ?string $avatar;
    public ?string $website;
    public ?string $about;
    public ?string $address;
    public ?string $currency;
    public ?array $socialMedia;

    /**
     * @param Merchant $model
     * @return MerchantDto
     */
    public function mapFromModel($model) : MerchantDto
    {
        $instance = self::instance();
        $instance->id = $model->id;
        $instance->name = $model->name;
        $instance->website = $model->website;
        $instance->address = $model->address;
        $instance->currency = $model->country?->currency;
        $instance->socialMedia = json_decode($model->extra_data['social_media'] ?? '{}',true);

        return $instance;
    }

    private static function instance(): MerchantDto
    {
        return new MerchantDto();
    }

    public static function map(?Merchant $model): ?MerchantDto
    {
        $instance = self::instance();
        if (!$model) {
            return null;
        }
        return $instance->mapFromModel($model);
    }

}
