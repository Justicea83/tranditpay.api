<?php

namespace App\Http\Controllers\v1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Services\Merchant\IMerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MerchantsController extends Controller
{
    private IMerchantService $merchantService;

    function __construct(IMerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function setup(CreateMerchantRequest $request): Response
    {
        $this->merchantService->setup($request->user(), $request->all());
        return $this->noContent();
    }

    public function getMerchants(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchants(request()->user()));
    }

    public function getPaymentTypes(int $merchantId): JsonResponse
    {
        return $this->successResponse($this->merchantService->getPaymentTypes(request()->user(), $merchantId));
    }

    public function getForm(int $merchantId, int $paymentTypeId): JsonResponse
    {
        return $this->successResponse($this->merchantService->getForm(request()->user(), $merchantId, $paymentTypeId));
    }

    public function getPaymentModes(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getPaymentModes(request()->user()));
    }
}
