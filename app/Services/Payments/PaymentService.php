<?php

namespace App\Services\Payments;

use App\Entities\Request\Payments\Paystack\PaystackMomoRequest;
use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\VerifyPaymentResponse;
use App\Models\Payment\PaymentApi;
use App\Models\User;
use App\Services\Payments\Paystack\IPaystackService;
use App\Utils\AppConstants;
use App\Utils\Payments\FlutterwaveUtility;
use App\Utils\Payments\PaystackUtility;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentService implements IPaymentService
{

    private IPaystackService $paystackService;

    function __construct
    (
        IPaystackService $paystackService,
    )
    {
        $this->paystackService = $paystackService;
    }

    public function collect(array $data, User $user, PaymentApi $paymentApi, ?float $amount = null, ?string $reference = null, ?string $currency = null): PaymentResponse
    {
        [
            'method' => $paymentMode
        ] = $data;

        $response = new PaymentResponse();

        if (config('app.env') == 'local') {
            $response->code = Response::HTTP_OK;
            $response->reference = $reference;
            $response->provider = PayStackUtility::NAME;
            $response->email = $user->email;
            $response->processed = false;
            return $response;
        }

        switch ($paymentApi->name) {
            case PaystackUtility::NAME:
                // if the payment mode is momo initiate the payment here
                [
                    'phone' => $phone,
                    'provider' => $provider,
                ] = $data['mobile_money'];
                if ($paymentMode === AppConstants::APP_PAYMENT_MODE_MOMO) {
                    return $this->paystackService
                        ->momoPay(
                            $user,
                            PaystackMomoRequest::instance()->setProvider($provider)
                                ->setPhone($phone)
                                ->setEmail($user->email ?? '')
                                ->setAmount($amount)
                                ->setReference($reference)
                                ->setCurrency($currency)
                        )
                        ->transformToPaymentResponse($user);
                }
                break;
            case FlutterwaveUtility::NAME:
                break;
        }
        return $response;
    }

    public function verifyTransaction(User $user, string $ref): VerifyPaymentResponse
    {
        // TODO: Implement verifyTransaction() method.
    }

    public function getPaymentModes(User $user): array
    {
        // TODO: Implement getPaymentModes() method.
    }

    public function processPayStackWebhookEvents(array $data): PaymentResponse
    {
        // TODO: Implement processPayStackWebhookEvents() method.
    }

    public function submitOtp(array $data)
    {
        // TODO: Implement submitOtp() method.
    }
}
