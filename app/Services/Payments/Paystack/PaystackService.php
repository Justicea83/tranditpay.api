<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use App\Entities\Request\Payments\Paystack\PaystackCardRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\User;
use App\Utils\Payments\PaystackUtility;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaystackService extends PaystackConfig implements IPaystackService
{

    public function initializePayment(PaystackCardRequest $request): PaystackResponse
    {
        // TODO: Implement initializePayment() method.
    }

    public function momoPay(User $user, PaystackMomoRequest $request): ?PaystackResponse
    {
        return $this->makeChargeCall((array)$request);
    }

    private function makeChargeCall(array $payload): PaystackResponse
    {
        return $this->call(PaystackUtility::CHARGE_ENDPOINT, $payload, 'post');
    }

    private function call(string $endpoint, array $payload = [], string $method = 'get'): PaystackResponse
    {
        $responseType = new PaystackResponse();

        try {
            $response = Http::baseUrl($this->baseUrl)
                ->withToken($this->secretKey)
                ->retry(3, 100)
                ->$method($endpoint, $payload);
            $status = $response->status();
            if ($status == Response::HTTP_OK) {
                $response = $response->json();
                return $responseType->setStatus($response['status'] ?? false)
                    ->setMessage($response['message'] ?? null)
                    ->setData($response['data'] ?? []);
            }
        } catch (Throwable) {
        }
        return $responseType;
    }

    public function verifyTransaction(string $ref): bool
    {
        $response = $this->call(PayStackUtility::VERIFY_TRANSACTION_ENDPOINT. $ref);

        return $response->status && $response->hasSuccessfulStatus();
    }
}
