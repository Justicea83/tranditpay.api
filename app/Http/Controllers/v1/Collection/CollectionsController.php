<?php

namespace App\Http\Controllers\v1\Collection;

use App\Http\Controllers\Controller;
use App\Services\Collection\ICollectionService;
use Illuminate\Http\JsonResponse;

class CollectionsController extends Controller
{
    private ICollectionService $collectionService;

    public function __construct(ICollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    public function loadCollections(string $type): JsonResponse
    {
        return $this->successResponse($this->collectionService->loadCollection($type, []));
    }

    public function loadCollectionsLevel2(string $type, int $typeId): JsonResponse
    {
        return $this->successResponse($this->collectionService->loadCollection($type, ['type_id' => $typeId]));
    }

    public function getCollectionTypes(): JsonResponse
    {
        return $this->successResponse($this->collectionService->getCollectionTypes());
    }
}
