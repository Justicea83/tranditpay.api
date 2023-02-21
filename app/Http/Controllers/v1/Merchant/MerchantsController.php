<?php

namespace App\Http\Controllers\v1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Services\Merchant\IMerchantService;
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
}
