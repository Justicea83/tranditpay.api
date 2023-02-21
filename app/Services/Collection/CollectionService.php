<?php

namespace App\Services\Collection;

use App\Models\Collection\Country;
use App\Models\Collection\State;
use App\Utils\CollectionUtils;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CollectionService implements ICollectionService
{

    private Country $countryModel;
    private State $stateModel;

    public function __construct(
        Country          $countryModel,
        State            $stateModel
    )
    {
        $this->countryModel = $countryModel;
        $this->stateModel = $stateModel;
    }


    public function loadCollection(string $type, array $payload): Collection
    {
        switch ($type) {
            case CollectionUtils::COLLECTION_TYPE_COUNTRIES:
                return $this->loadCountries();
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


    public function getCollectionTypes(): array
    {
        return CollectionUtils::COLLECTION_TYPES;
    }
}
