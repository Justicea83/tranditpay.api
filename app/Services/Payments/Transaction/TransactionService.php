<?php

namespace App\Services\Payments\Transaction;

use App\Entities\PendingRequests\PendingAction;
use App\Entities\PendingRequests\PendingPayFromForm;
use App\Models\Payment\PaymentApi;
use App\Models\User;
use App\Utils\AppUtils;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class TransactionService implements ITransactionService
{
    private PaymentApi $paymentApi;

    public function __construct(PaymentApi $paymentApi)
    {
        $this->paymentApi = $paymentApi;
    }

    public function processPayment(User $user, int $merchantId, array $payload)
    {
        // TODO: Implement processPayment() method.
    }

    public function createPendingAction(User $user, array $payload): array
    {
        [
            'type' => $type,
            'amount' => $amount,
        ] = $payload;

        /** @var PaymentApi $activeProvider */
        $activeProvider = $this->paymentApi->query()->active()->first();
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
                        ->setProvider($activeProvider->name);
                    break;
                default:
                    throw new InvalidArgumentException('Invalid type provided');
            }
            $user->pendingRequests()->create([
                'reference' => $ref,
                'payload' => serialize($pendingAction),
            ]);

            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            throw $t;
        }

        return [
            'reference' => $ref,
            'provider' => $activeProvider->name
        ];
    }
}
