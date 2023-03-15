<?php

namespace App\Services\Payments\Transaction;

use App\Entities\PendingRequests\PendingAction;
use App\Entities\PendingRequests\PendingPayFromForm;
use App\Entities\Response\Payments\PaymentResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\User;
use App\Services\Payments\IPaymentService;
use App\Utils\AppUtils;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TransactionService implements ITransactionService
{
    private PaymentApi $paymentApi;
    private IPaymentService $paymentService;
    private Merchant $merchant;

    public function __construct(PaymentApi $paymentApi, IPaymentService $paymentService, Merchant $merchant)
    {
        $this->paymentApi = $paymentApi;
        $this->paymentService = $paymentService;
        $this->merchant = $merchant;
    }

    public function processPayment(User $user, int $merchantId, array $payload)
    {
        // TODO: Implement processPayment() method.
    }

    public function createPendingAction(User $user, array $payload): PaymentResponse
    {
        [
            'type' => $type,
            'amount' => $amount,
            'payment_info' => $paymentInfo,
            'merchant_id' => $merchantId,
        ] = $payload;

        /** @var PaymentApi $activeProvider */
        $activeProvider = $this->paymentApi->query()->active()->first();

        /** @var Merchant $merchant */
        $merchant = $this->merchant->query()->find($merchantId);

        $ref = AppUtils::getToken();

        try {
            DB::beginTransaction();

            switch ($type) {
                case PendingAction::TYPE_FROM_FORM:
                    $form = $payload['form'];
                    $pendingAction = PendingPayFromForm::instance()
                        ->setAmount($amount)
                        ->setResponses($form['responses'])
                        ->setPaymentTypeId($form['payment_type_id'])
                        ->setMerchantId($payload['merchant_id'])
                        ->setProvider($activeProvider->name);
                    break;
                default:
                    throw new InvalidArgumentException('Invalid type provided');
            }
            $user->pendingRequests()->create([
                'reference' => $ref,
                'payload' => serialize($pendingAction),
            ]);

            $response = $this->paymentService->collect($paymentInfo, $user, $activeProvider, $amount, $ref, $merchant->country->currency);

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        return $response;
    }
}
