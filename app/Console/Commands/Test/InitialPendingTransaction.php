<?php

namespace App\Console\Commands\Test;

use App\Entities\PendingRequests\PendingAction;
use App\Models\Payment\PaymentMode;
use App\Models\User;
use App\Services\Merchant\IMerchantService;
use App\Services\Payments\Transaction\ITransactionService;
use App\Utils\FormUtils;
use Faker\Factory;
use Illuminate\Console\Command;

class InitialPendingTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:create_pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command allows you to initial a pending transaction for testing';

    /**
     * Execute the console command.
     */
    public function handle(IMerchantService $merchantService, ITransactionService $transactionService): void
    {
        $amount = $this->ask('How much are you paying');
        $merchantId = $this->ask('What is the merchant Id?');
        $paymentTypeId = $this->ask('What is the payment type ID?');
        $paymentMethod = $this->choice('What is the payment method', PaymentMode::query()->pluck('name')->toArray(), 1);
        $status = $this->choice('What should be the status of the transaction?', ['success', 'failure'], 0);

        // any user at all
        /** @var User $user */
        $user = User::query()->inRandomOrder()->first();

        $form = $merchantService->getForm($user, $merchantId, $paymentTypeId);

        $faker = Factory::create();

        $responses = [];

        foreach ($form['sections'] as $formSection) {
            foreach ($formSection['forms'] as $form) {
                $answer = '';
                switch ($form['type']) {
                    case FormUtils::PARAGRAPH:
                    case FormUtils::SHORT_ANSWER:
                        $answer = $faker->words(random_int(0, 10), true);
                        break;
                    case FormUtils::MULTIPLE_CHOICES:
                    case FormUtils::DROPDOWN:
                    case FormUtils::CHECKBOXES:
                        $answer = $form['options'][random_int(0, count($form['options']) - 1)]['id'];
                        break;
                    case FormUtils::DATE:
                        $answer = $faker->date;
                        break;
                    case FormUtils::TIME:
                        $answer = $faker->time;
                        break;
                }
                $responses[] = [
                    'form_field_id' => $form['id'],
                    'value' => $answer
                ];
            }
        }

        $payload = [
            'type' => PendingAction::TYPE_FROM_FORM,
            'amount' => $amount,
            //'tax' => $faker->randomFloat(null, 2, 40),
            'tax' => 0,
            'payment_info' => [
                'method' => $paymentMethod,
                $paymentMethod => [
                    'provider' => 'MTN',
                    'phone' => $status === 'success' ? '0243742088' : $faker->phoneNumber
                ]
            ],
            'merchant_id' => $merchantId,
            'form' => [
                'payment_type_id' => $paymentTypeId,
                'responses' => $responses
            ]
        ];

        $response = $transactionService->createPendingAction($user, $payload);

        $this->info(json_encode($response));
        $this->newLine();
        $this->info('The command was successful!');
    }
}
