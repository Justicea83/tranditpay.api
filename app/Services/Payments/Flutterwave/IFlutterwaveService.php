<?php

namespace App\Services\Payments\Flutterwave;

use App\Entities\Request\Payments\Flutterwave\FlutterwaveCardRequest;
use App\Entities\Request\Payments\Flutterwave\FlutterwaveMomoRequest;
use App\Entities\Response\Payments\Flutterwave\FlutterwaveResponse;
use App\Models\User;
use Illuminate\Support\Collection;

interface IFlutterwaveService
{
    public function buildCashbackMomoPayload(User $user, array $data, float $amount, string $ref): FlutterwaveMomoRequest;

    public function initializeMomoPayment(FlutterwaveMomoRequest $request, string $countryCode): FlutterwaveResponse;

    public function validateCharge(string $otp, string $flutterwaveRef, string $type = 'card'): FlutterwaveResponse;

    public function initializeCardPayment(FlutterwaveCardRequest $request, bool $encrypt = false): FlutterwaveResponse;

    public function buildCashbackCardPayload(User $user, array $data, float $amount, string $ref): FlutterwaveCardRequest;

    public function getBanksForCountry(string $countryCode): FlutterwaveResponse;

    public function getMobileMoneyProvidersForCountry(string $countryCode): Collection;
}
