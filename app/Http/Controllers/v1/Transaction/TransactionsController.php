<?php

namespace App\Http\Controllers\v1\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CreatePendingRequest;
use App\Services\Payments\Transaction\ITransactionService;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    private ITransactionService $transactionService;

    public function __construct(ITransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function createPendingAction(CreatePendingRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->transactionService->createPendingAction($request->user(), $request->all())
        );
    }
}
