<?php

namespace App\Services\Collection;

use App\Models\Collection\Country;
use App\Models\Collection\State;
use App\Services\Merchant\IMerchantService;
use App\Utils\CacheKeys;
use App\Utils\CollectionUtils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Stevebauman\Location\Facades\Location;

class CollectionService implements ICollectionService
{

    public function __construct(
        private readonly Country          $countryModel,
        private readonly State            $stateModel,
        private readonly IMerchantService $merchantService,
    )
    {
    }


    public function loadCollection(string $type, array $payload): Collection|Model
    {
        switch ($type) {
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES:
                return $this->loadCountries();
            case CollectionUtils::COLLECTION_TYPE_LOCATION:
                return $this->loadLocation();
            case CollectionUtils::COLLECTION_TYPE_MERCHANTS:
                return $this->merchantService->getAllMerchants(null);
            case CollectionUtils::COLLECTION_TYPE_MERCHANTS_PAYMENT_TYPES:
                ['type_id' => $typeId] = $payload;
                return $this->merchantService->getAllPaymentTypes(null, $typeId);
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES_STATES:
                ['type_id' => $typeId] = $payload;
                return $this->loadCountryStates($typeId);

            default:
                throw new InvalidArgumentException("the collection type cannot be found");
        }
    }

    private function loadCountries(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->countryModel->query()->get();
    }

    private function loadCountryStates(int $countryId): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->stateModel->query()->where('country_id', $countryId)->get();
    }

    private function loadLocation(): ?Country
    {
        if (auth()->check()) {
            $user = request()->user();
            $cacheKey = CacheKeys::getKeyForUser($user, CacheKeys::USER_LOCATION);

            if (Cache::has($cacheKey)) {
                $locationInfo = Cache::get($cacheKey);
            } else {
                $currentUserInfo = Location::get(app()->environment(['local']) ? '102.176.0.43' : request()->ip());
                Cache::put($cacheKey, $currentUserInfo);
                $locationInfo = $currentUserInfo;
            }
        } else {
            $locationInfo = Location::get(app()->environment(['local']) ? '102.176.0.43' : request()->ip());

        }
        return Country::findByISO2($locationInfo->countryCode);
    }


    public function getCollectionTypes(): array
    {
        return CollectionUtils::COLLECTION_TYPES;
    }
}
