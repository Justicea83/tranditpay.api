<?php

namespace App\Services\Payments\Transaction;

use App\Dto\Payments\TransactionDto;
use App\Entities\PendingRequests\PendingAction;
use App\Entities\PendingRequests\PendingPayFromForm;
use App\Entities\Response\Payments\PaymentResponse;
use App\Models\Form\FormField;
use App\Models\Form\FormResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentApi;
use App\Models\Payment\PaymentMode;
use App\Models\Payment\PaymentType;
use App\Models\Payment\PendingRequest;
use App\Models\Payment\Transaction;
use App\Models\User;
use App\Services\Payments\IPaymentService;
use App\Utils\AppUtils;
use App\Utils\Payments\Enums\FundsLocation;
use App\Utils\Payments\Enums\TransactionStatus;
use App\Utils\StatusUtils;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TransactionService implements ITransactionService
{
    public function __construct(
        private readonly PaymentApi      $paymentApi,
        private readonly IPaymentService $paymentService,
        private readonly Merchant        $merchant,
        private readonly PendingRequest  $pendingRequest,
        private readonly FormResponse    $formResponse,
        private readonly FormField       $formField,
        private readonly PaymentMode     $paymentMode,
        private readonly Transaction     $transaction,
    )
    {
    }

    public function processPayment(User $user, int $merchantId, array $payload)
    {
        // TODO: Implement processPayment() method.
    }

    private function getActivePaymentApi(): PaymentApi
    {
        /** @var PaymentApi $activeProvider */
        $activeProvider = $this->paymentApi->query()->active()->first();
        return $activeProvider;
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

        $activePaymentProvider = $this->getActivePaymentApi();

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
                        ->setProvider($activePaymentProvider->name);
                    break;
                default:
                    throw new InvalidArgumentException('Invalid type provided');
            }
            $user->pendingRequests()->create([
                'reference' => $ref,
                'payload' => serialize($pendingAction),
                'type' => PendingAction::TYPE_FROM_FORM
            ]);

            $response = $this->paymentService->collect($paymentInfo, $user, $activePaymentProvider, $amount + $taxAmount, $ref, $merchant->country->currency);

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        return $response;
    }

    /**
     * @throws Throwable
     */
    public function processPendingRequests()
    {
        $this->pendingRequest->query()->where('status', StatusUtils::PENDING)
            ->chunkById(100, function (Collection $pendingRequests) {
                /** @var PendingRequest $pendingRequest */
                foreach ($pendingRequests as $pendingRequest) {
                    $this->processPendingRequest($pendingRequest);
                }
            });
    }

    public function processPendingRequest(PendingRequest $pendingRequest)
    {
        try {
            DB::beginTransaction();

            switch ($pendingRequest->type) {
                case PendingAction::TYPE_FROM_FORM:
                    /** @var PendingPayFromForm $payload */
                    $payload = unserialize($pendingRequest->payload);

                    if (
                        $this->paymentService->verifyTransaction($payload->provider, $pendingRequest->reference)->valid
                    ) {
                        Transaction::builder()
                            ->setReference($pendingRequest->reference)
                            ->setTaxAmount($payload->taxAmount)
                            ->setAmount($payload->amount)
                            ->setFundsLocation(FundsLocation::Application->value)
                            ->setStatus(TransactionStatus::Completed->value)
                            ->setMerchantId($payload->merchantId)
                            ->setUserId($pendingRequest->owner_id)
                            ->setModelId($payload->payment_type_id)
                            ->setModelType(PaymentType::class)
                            ->setPaymentMethod($payload->method)
                            ->setCurrency($payload->currency)
                            ->create();

                        if ($payload->responses) {
                            $firstResponse = $payload->responses[0]['form_field_id'];
                            /** @var FormField $formField */
                            $formField = $this->formField->query()->find($firstResponse);

                            if ($formField) {
                                /** @var FormResponse $formResponse */
                                $formResponse = $this->formResponse->query()->create([
                                    'form_id' => $formField->formSection->form->id,
                                    'reference' => $pendingRequest->reference,
                                    'user_id' => $pendingRequest->owner_id
                                ]);

                                foreach ($payload->responses as $response) {
                                    $formResponse->fieldResponses()->create([
                                        'form_field_id' => $response['form_field_id'],
                                        'response' => $response['value']
                                    ]);
                                }
                            }
                        }

                        $pendingRequest->status = StatusUtils::COMPLETED;
                        $pendingRequest->save();
                    }
                    break;
            }

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

    }

    public function getTransactionApplicableTax(User $user, array $payload): array
    {
        [
            'merchant_id' => $merchantId,
            'payment_method' => $paymentMethod
        ] = $payload;

        /** @var Merchant $merchant */
        $merchant = $this->merchant->query()->find($merchantId);
        $activePaymentProvider = $this->getActivePaymentApi();

        /** @var PaymentMode $paymentMode */
        $paymentMode = $this->paymentMode->query()->where('name', $paymentMethod)->first();

        return DB::select(
            "
                SELECT id, name, rate_type, rate_amount
                FROM taxes
                WHERE (
                    country_code = :country_code
                     AND
                    (payment_api_id = :payment_api_id OR payment_api_id is NULL)
                    AND
                    (payment_mode_id = :payment_mode_id OR payment_mode_id is NULL)
                    AND
                    (merchant_id = :merchant_id OR merchant_id is NULL)
                    AND
                    (start_date <= now() OR start_date is NULL)
                    AND
                    (end_date >= now() OR end_date is NULL)
                )
            ",
            [
                'country_code' => $merchant->country->iso2,
                'payment_api_id' => $activePaymentProvider?->id,
                'payment_mode_id' => $paymentMode?->id,
                'merchant_id' => $merchantId
            ]
        );
    }

    public function getTransactions(User $user): Paginator
    {
        $pageSize = request()->query->get('pageSize') ?? 20;
        $page = request()->query->get('pageIndex') ?? 1;

        $pagedData = $this->transaction
            ->query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->simplePaginate($pageSize, ['*'], 'page', $page);

        $pagedData->getCollection()->transform(function (Transaction $transaction) {
            return TransactionDto::map($transaction);
        });

        return $pagedData;
    }
}
