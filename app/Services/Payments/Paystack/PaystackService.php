<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use App\Entities\Request\Payments\Paystack\PaystackCardRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Request\Payments\Paystack\PaystackReceiptRequest;
use App\Entities\Request\Payments\Paystack\PaystackTransferRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\User;
use App\Utils\Payments\PaystackUtility;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        Log::info("----------------------PAYSTACK REQUEST -------------------", $payload);

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::baseUrl($this->baseUrl)
                ->withToken($this->secretKey)
                ->retry(3, 100)
                ->$method($endpoint, $payload);
            $status = $response->status();
            Log::info(get_class(), ['status' => $status, 'message' => $response->body()]);
            if ($status >= Response::HTTP_OK && $status < 300) {
                $response = $response->json();
                Log::info(get_class(), $response);
                return $responseType->setStatus($response['status'] ?? false)
                    ->setMessage($response['message'] ?? null)
                    ->setData($response['data'] ?? []);
            }
        } catch (Throwable $t) {
            Log::error(get_class(), [$t->getTrace()]);
        }
        return $responseType;
    }

    public function verifyTransaction(string $ref): bool
    {
        $response = $this->call(PayStackUtility::VERIFY_TRANSACTION_ENDPOINT . $ref);

        return $response->status && $response->hasSuccessfulStatus();
    }

    public function createTransferReceipt(PaystackReceiptRequest $payload): PaystackResponse
    {
        return $this->call(PaystackUtility::TRANSFER_RECEIPT_ENDPOINT, (array)$payload, 'post');
    }

    public function singleTransfer(PaystackTransferRequest $payload): PaystackResponse
    {
        return $this->call(PaystackUtility::TRANSFER_ENDPOINT, (array)$payload, 'post');
    }
}
