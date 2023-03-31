<?php

namespace App\Services\Payments\Flutterwave;

use App\Entities\Request\Payments\Flutterwave\FlutterwaveCardRequest;
use App\Entities\Request\Payments\Flutterwave\FlutterwaveMomoRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Response\Payments\Flutterwave\FlutterwaveResponse;
use App\Entities\Response\Payments\Paystack\PaystackResponse;
use App\Models\Merchant\Merchant;
use App\Models\User;
use Illuminate\Support\Collection;

interface IFlutterwaveService
{
    public function buildCashbackMomoPayload(User $user, array $data, float $amount, string $ref): FlutterwaveMomoRequest;

    public function validateCharge(string $otp, string $flutterwaveRef, string $type = 'card'): FlutterwaveResponse;

    public function initializeCardPayment(FlutterwaveCardRequest $request, bool $encrypt = false): FlutterwaveResponse;

    public function buildCashbackCardPayload(User $user, array $data, float $amount, string $ref): FlutterwaveCardRequest;

    public function getBanksForCountry(string $countryCode): FlutterwaveResponse;

    public function getMobileMoneyProvidersForCountry(string $countryCode): Collection;

    public function momoPay(Merchant $merchant,User $user, FlutterwaveMomoRequest $request): ?FlutterwaveResponse;

}
