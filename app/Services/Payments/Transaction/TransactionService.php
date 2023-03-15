<?php

namespace App\Services\Payments\Transaction;

use App\Entities\PendingRequests\PendingAction;
use App\Entities\PendingRequests\PendingPayFromForm;
use App\Entities\Response\Payments\PaymentResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\Payment\PendingRequest;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Services\Payments\IPaymentService;
use App\Utils\AppUtils;
use App\Utils\Payments\Enums\FundsLocation;
use App\Utils\Payments\Enums\TransactionStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TransactionService implements ITransactionService
{
    private PaymentApi $paymentApi;
    private IPaymentService $paymentService;
    private Merchant $merchant;
    private PendingRequest $pendingRequest;

    public function __construct(
        PaymentApi $paymentApi, IPaymentService $paymentService, Merchant $merchant, PendingRequest $pendingRequest
    )
    {
        $this->paymentApi = $paymentApi;
        $this->paymentService = $paymentService;
        $this->merchant = $merchant;
        $this->pendingRequest = $pendingRequest;
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
            'tax' => $taxAmount,
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
                        ->setTaxAmount($taxAmount)
                        ->setResponses($form['responses'])
                        ->setPaymentTypeId($form['payment_type_id'])
                        ->setMerchantId($payload['merchant_id'])
                        ->setMethod($paymentInfo['method'])
                        ->setCurrency($merchant->country->currency)
                        ->setProvider($activeProvider->name);
                    break;
                default:
                    throw new InvalidArgumentException('Invalid type provided');
            }
            $user->pendingRequests()->create([
                'reference' => $ref,
                'payload' => serialize($pendingAction),
                'type' => PendingAction::TYPE_FROM_FORM
            ]);

            $response = $this->paymentService->collect($paymentInfo, $user, $activeProvider, $amount + $taxAmount, $ref, $merchant->country->currency);

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        return $response;
    }

    public function processPendingRequests()
    {
        $this->pendingRequest->query()->where('status', TransactionStatus::Pending->value)
            ->chunkById(100, function (Collection $pendingRequests) {
                /** @var PendingRequest $pendingRequest */
                foreach ($pendingRequests as $pendingRequest) {
                    $this->processPendingRequest($pendingRequest);
                }
            });
    }

    private function processPendingRequest(PendingRequest $pendingRequest)
    {
        switch ($pendingRequest->type) {
            case PendingAction::TYPE_FROM_FORM:
                /** @var PendingPayFromForm $payload */
                $payload = unserialize($pendingRequest->payload);

                if (
                    $this->paymentService->verifyTransaction($payload->provider, $pendingRequest->reference)->valid
                ) {
                    // TODO submit the filled form
                    // TODO create a transaction
                    $transaction = Transaction::builder()
                        ->setReference($pendingRequest->reference)
                        ->setTaxAmount($payload->taxAmount)
                        ->setAmount($payload->amount)
                        ->setFundsLocation(FundsLocation::Application->value)
                        ->setStatus(TransactionStatus::Completed->value)
                        ->setMerchantId($payload->merchantId)
                        ->setUserId($pendingRequest->owner_id)
                        ->setPaymentMethod($payload->method)
                        ->setCurrency($payload->currency)
                        ->create();
                }
                break;
        }
    }
}
