<?php

namespace App\Services\Payments;

use App\Entities\Request\Payments\Flutterwave\FlutterwaveMomoRequest;
use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\VerifyPaymentResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\Payment\Settlement;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Services\Payments\Flutterwave\IFlutterwaveService;
use App\Services\Payments\Paystack\IPaystackService;
use App\Utils\AppConstants;
use App\Utils\Payments\Enums\FundsLocation;
use App\Utils\Payments\Enums\TransactionStatus;
use App\Utils\Payments\FlutterwaveUtility;
use App\Utils\Payments\PaystackUtility;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentService implements IPaymentService
{

    function __construct
    (
        private readonly IPaystackService    $paystackService,
        private readonly IFlutterwaveService $flutterwaveService,
        private readonly Transaction         $transaction,
        private readonly Settlement          $settlement
    )
    {
    }

    public function collect(array $data, User $user, PaymentApi $paymentApi, Merchant $merchant, ?float $amount = null, ?string $reference = null): PaymentResponse
    {
        [
            'method' => $paymentMode
        ] = $data;

        $response = new PaymentResponse();

        /*if (config('app.env') == 'local') {
            $response->code = Response::HTTP_OK;
            $response->reference = $reference;
            $response->provider = PayStackUtility::NAME;
            $response->email = $user->email;
            $response->processed = false;
            return $response;
        }*/
        $currency = $merchant->country->currency;

        switch ($paymentApi->name) {
            case PaystackUtility::NAME:
                // if the payment mode is momo initiate the payment here
                if ($paymentMode === AppConstants::APP_PAYMENT_MODE_MOMO) {
                    [
                        'phone' => $phone,
                        'provider' => $provider,
                    ] = $data['mobile_money'];
                    return $this->paystackService
                        ->momoPay(
                            $user,
                            PaystackMomoRequest::instance()->setProvider($provider)
                                ->setPhone($phone)
                                ->setEmail($user->email ?? '')
                                ->setAmount($amount)
                                ->setReference($reference)
                                ->setCurrency($currency)
                        )->transformToPaymentResponse($user);
                }
                if ($paymentMode === AppConstants::APP_PAYMENT_MODE_CARD) {
                    return $response->setReference($reference)->setProcessed(false)->setProvider(PaystackUtility::NAME)->setCode(Response::HTTP_OK);
                }
                break;
            case FlutterwaveUtility::NAME:
                if ($paymentMode === AppConstants::APP_PAYMENT_MODE_MOMO) {
                    [
                        'phone' => $phone,
                        'provider' => $provider,
                    ] = $data['mobile_money'];
                    return $this->flutterwaveService
                        ->momoPay(
                            $merchant,
                            $user,
                            FlutterwaveMomoRequest::instance()
                                ->setPhoneNumber($phone)
                                ->setEmail($user->email ?? '')
                                ->setAmount($amount)
                                ->setTxRef($reference)
                                ->setNetwork($provider)
                                ->setCurrency($currency)
                        )->transformToPaymentResponse($user);
                }
                break;
        }
        return $response;
    }

    public function verifyTransaction(string $provider, string $ref): VerifyPaymentResponse
    {
        if (config('app.env') == 'local') {
            return VerifyPaymentResponse::instance()
                ->setReference($ref)
                ->setValid(true);
        }

        switch ($provider) {
            case PaystackUtility::NAME:
                return VerifyPaymentResponse::instance()
                    ->setReference($ref)
                    ->setValid($this->paystackService->verifyTransaction($ref));
            case FlutterwaveUtility::NAME:
                break;
        }
        return VerifyPaymentResponse::instance();
    }

    public function getPaymentModes(User $user): array
    {
        // TODO: Implement getPaymentModes() method.
    }

    public function submitOtp(array $data)
    {
        // TODO: Implement submitOtp() method.
    }

    public function settlePaystackTransfer(string $event, array $data)
    {
        ['reason' => $reason, 'reference' => $reference] = $data;
        $transactionReference = explode('/', $reason)[0];

        /** @var Transaction $transaction */
        $transaction = $this->transaction->query()->where('reference', $transactionReference)->first();

        if (!$transaction) {
            return;
        }

        $status = TransactionStatus::Completed->value;

        if ($event === PaystackUtility::EVENT_TRANSFER_FAILED) {
            $status = TransactionStatus::Failed->value;
        }

        if ($event === PaystackUtility::EVENT_TRANSFER_REVERSED) {
            $status = TransactionStatus::Refunded->value;
        }

        /** @var Settlement $settlement */
        $settlement = $this->settlement->query()->firstOrCreate([
            'reference' => $reference,
            'transaction_id' => $transaction->id,
            'merchant_id' => $transaction->merchant_id,
        ]);

        $settlement->status = $status;
        $settlement->save();

        $transaction->funds_location = FundsLocation::Merchant->value;
    }

}
