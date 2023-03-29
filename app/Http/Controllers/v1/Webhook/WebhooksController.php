<?php

namespace App\Http\Controllers\v1\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhooks\Payments\PaystackEventRequest;
use App\Services\Webhook\IWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhooksController extends Controller
{
    public function __construct(private readonly IWebhookService $webhookService)
    {
    }

    public function paystackEvent(PaystackEventRequest $request): JsonResponse
    {
        $this->webhookService->paystackEvent($request->all());
        return $this->successResponse();
    }
}
