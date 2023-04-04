<?php

namespace App\Services\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Request\Payments\Paystack\PaystackReceiptRequest;
use App\Entities\Request\Payments\Paystack\PaystackTransferRequest;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\Bank;
use App\Models\User;
use App\Utils\Payments\PaystackUtility;
use App\Utils\StatusUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaystackService extends PaystackConfig implements IPaystackService
{
    public function __construct(
        private readonly Bank $bank
    )
    {
        parent::__construct();
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

    public function getMomoProviders(Merchant $merchant): Collection
    {
        $banks = $this->bank->query()
            ->where('country_id', $merchant->country_id)
            ->where('status', StatusUtils::ACTIVE)
            ->where('extra_info->paystack->type', 'mobile_money')
            ->get()
            ->map(function (Bank $bank) {
                $extraInfo = json_decode($bank->extra_info, true)['paystack'];
                return [
                    'name' => $extraInfo['name'],
                    'code' => $extraInfo['code'],
                ];
            });

        if (!$banks->count()) {
            \App\Entities\Payments\Paystack\Bank::instance()->fetchAll($merchant->country->name);
            return $this->getMomoProviders($merchant);
        } else {
            return $banks;
        }
    }
}
