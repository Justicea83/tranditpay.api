<?php

namespace App\Services\Payments\Flutterwave;

use App\Entities\Payments\Flutterwave\Config\FlutterwaveConfig;
use App\Entities\Request\Payments\Flutterwave\FlutterwaveCardRequest;
use App\Entities\Request\Payments\Flutterwave\FlutterwaveMomoRequest;
use App\Entities\Response\Payments\Flutterwave\FlutterwaveResponse;
use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Utils\Payments\FlutterwaveUtility;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService extends FlutterwaveConfig implements IFlutterwaveService
{

    public function buildCashbackMomoPayload(User $user, array $data, float $amount, string $ref): FlutterwaveMomoRequest
    {
        // TODO: Implement buildCashbackMomoPayload() method.
    }

    public function validateCharge(string $otp, string $flutterwaveRef, string $type = 'card'): FlutterwaveResponse
    {
        // TODO: Implement validateCharge() method.
    }

    public function initializeCardPayment(FlutterwaveCardRequest $request, bool $encrypt = false): FlutterwaveResponse
    {
        // TODO: Implement initializeCardPayment() method.
    }

    public function buildCashbackCardPayload(User $user, array $data, float $amount, string $ref): FlutterwaveCardRequest
    {
        // TODO: Implement buildCashbackCardPayload() method.
    }

    public function getBanksForCountry(string $countryCode): FlutterwaveResponse
    {
        // TODO: Implement getBanksForCountry() method.
    }

    public function getMobileMoneyProvidersForCountry(string $countryCode): Collection
    {
        // TODO: Implement getMobileMoneyProvidersForCountry() method.
    }

    public function momoPay(Merchant $merchant, User $user, FlutterwaveMomoRequest $request): ?FlutterwaveResponse
    {
        $endpoint = FlutterwaveUtility::ENDPOINT_CHARGES;

        if($merchant->country->iso2 === 'GH') {
            $endpoint = sprintf('%s?type=%s', $endpoint, 'mobile_money_ghana');
        }

        return $this->call($endpoint, (array) $request, 'post');
    }

    private function call(string $endpoint, array $payload = [], string $method = 'get'): FlutterwaveResponse
    {
        Log::info("----------------------FLUTTERWAVE REQUEST -------------------", $payload);
        $responseType = new FlutterwaveResponse();

        /** @var Response $response */
        $response = Http::baseUrl($this->baseUrl)
            ->withToken($this->secretKey)
            ->retry(3, 100)
            ->$method($endpoint, $payload);

        $status = $response->status();

        if ($status == \Symfony\Component\HttpFoundation\Response::HTTP_OK) {
            $response = $response->json();
            Log::info("----------------------FLUTTERWAVE RESPONSE -------------------", $response);
            return $responseType->setStatus($response['status'] ?? FlutterwaveUtility::FAIL_RESPONSE)
                ->setMessage($response['message'] ?? null)
                ->setData($response['data'] ?? [])
                ->setMeta($response['meta'] ?? []);
        }

        return $responseType;
    }
}
