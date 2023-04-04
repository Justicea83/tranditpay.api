<?php

namespace App\Http\Controllers\v1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Services\Merchant\IMerchantService;
use App\Services\Payments\IPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MerchantsController extends Controller
{
    function __construct(private readonly IMerchantService $merchantService, private readonly IPaymentService $paymentService)
    {
    }

    public function setup(CreateMerchantRequest $request): Response
    {
        $this->merchantService->setup($request->user(), $request->all());
        return $this->noContent();
    }

    public function getMerchants(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchants(request()->user() ?? null));
    }

    public function getAllMerchants(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getAllMerchants(request()->user() ?? null));
    }

    public function getPaymentTypes(int $merchantId): JsonResponse
    {
        return $this->successResponse($this->merchantService->getPaymentTypes(request()->user() ?? null, $merchantId));
    }

    public function getForm(int $merchantId, int $paymentTypeId): JsonResponse
    {
        return $this->successResponse($this->merchantService->getForm(request()->user() ?? null, $merchantId, $paymentTypeId));
    }

    public function getPaymentModes(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getPaymentModes(request()->user() ?? null));
    }

    public function getMomoProviders(int $merchantId): JsonResponse
    {
        return $this->successResponse($this->paymentService->getMomoProviders($merchantId));
    }
}
