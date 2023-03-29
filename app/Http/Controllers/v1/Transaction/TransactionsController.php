<?php

namespace App\Http\Controllers\v1\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\ApplicableTaxRequest;
use App\Http\Requests\Transaction\CreatePendingRequest;
use App\Services\Payments\Transaction\ITransactionService;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    public function __construct(private readonly ITransactionService $transactionService)
    {
    }

    public function createPendingAction(CreatePendingRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->transactionService->createPendingAction($request->user(), $request->all())
        );
    }

    public function getTransactionApplicableTax(ApplicableTaxRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->transactionService->getTransactionApplicableTax($request->user(), $request->validated())
        );
    }

    public function getTransactions(): JsonResponse
    {
        return $this->successResponse(
            $this->transactionService->getTransactions(request()->user())
        );
    }
}
