<?php

namespace App\Services\Merchant;

use App\Dto\Merchant\MerchantDto;
use App\Models\Collection\Country;
use App\Models\Form\FormField;
use App\Models\Form\FormFieldOption;
use App\Models\Form\FormSection;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PaymentMode;
use App\Models\Payment\PaymentType;
use App\Models\User;
use App\Services\Payments\Transaction\ITransactionService;
use App\Services\UserManagement\IUserManagementService;
use App\Utils\CacheKeys;
use App\Utils\StatusUtils;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class MerchantService implements IMerchantService
{
    private IUserManagementService $userManagementService;
    private ITransactionService $transactionService;
    private Merchant $merchantModel;
    private PaymentType $paymentTypeModel;
    private PaymentMode $paymentModeModel;

    function __construct(
        IUserManagementService $userManagementService,
        Merchant               $merchantModel,
        PaymentType            $paymentTypeModel,
        PaymentMode            $paymentModeModel,
        ITransactionService    $transactionService
    )
    {
        $this->userManagementService = $userManagementService;
        $this->merchantModel = $merchantModel;
        $this->paymentTypeModel = $paymentTypeModel;
        $this->paymentModeModel = $paymentModeModel;
        $this->transactionService = $transactionService;
    }

    /**
     * @throws Exception
     */
    public function setup(?User $user, array $payload)
    {
        [
            'user' => $userData,
            'merchant' => $merchant
        ] = $payload;

        DB::beginTransaction();

        try {
            /** @var User $user */
            $user = $this->userManagementService->createUser($userData);

            $merchant['owner_id'] = $user->id;
            $this->createMerchant($user, $merchant);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * @throws Exception
     */
    public function createMerchant(?User $user, array $payload): ?Model
    {
        /** @var Merchant $merchant */
        $merchant = $this->merchantModel->query()->create($payload);

        return $merchant;
    }

    public function getMerchants(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('pageSize') ?? 20;
        $page = request()->query->get('pageIndex') ?? 1;
        $search = request()->query->get('query') ?? '';

        $pagedData = $this->merchantModel
            ->search($search)
            ->where('status', StatusUtils::ACTIVE)
            ->paginate($pageSize, 'page', $page);

        $pagedData->getCollection()->transform(function (Merchant $merchant) {
            return MerchantDto::map($merchant);
        });

        return $pagedData;
    }

    public function getPaymentTypes(User $user, int $merchantId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('pageSize') ?? 20;
        $page = request()->query->get('pageIndex') ?? 1;
        $search = request()->query->get('query') ?? '';

        $pagedData = $this->paymentTypeModel
            ->search($search)
            ->where('merchant_id', $merchantId)
            ->where('status', StatusUtils::ACTIVE)
            ->paginate($pageSize, 'page', $page);

        $pagedData->getCollection()->transform(function (PaymentType $paymentType) {
            return [
                'id' => $paymentType->id,
                'name' => $paymentType->name,
            ];
        });

        return $pagedData;
    }

    public function getForm(User $user, int $merchantId, int $paymentTypeId): array
    {
        /** @var PaymentType $paymentType */
        $paymentType = $this->paymentTypeModel->query()->find($paymentTypeId);
        if (!$paymentType || !$paymentType->form) {
            return [];
        }

        $form = $paymentType->form;

        return [
            'paymentType' => [
                'id' => $paymentType->id,
                'name' => $paymentType->name,
            ],
            'sections' => $form->sections()->orderBy('id')->get()->map(function (FormSection $section) {
                return [
                    'id' => $section->id,
                    'description' => $section->description,
                    'title' => $section->title,
                    'forms' => $section->fields()->orderBy('id')->get()->map(function (FormField $formField) {
                        return [
                            'id' => $formField->id,
                            'required' => $formField->required,
                            'label' => $formField->label,
                            'type' => $formField->formFieldType->name,
                            'options' => $formField->options()->orderBy('id')->get()->map(function (FormFieldOption $option) {
                                return [
                                    'id' => $option->id,
                                    'name' => $option->name,
                                ];
                            })
                        ];
                    })
                ];
            })
        ];
    }

    public function getPaymentModes(User $user): Collection
    {
        $cacheKey = CacheKeys::getKeyForUser($user, CacheKeys::USER_LOCATION);

        if (Cache::has($cacheKey)) {
            $currentUserInfo = Cache::get($cacheKey);
        } else {
            $currentUserInfo = Location::get(app()->environment(['local']) ? '102.176.0.43' : request()->ip());
            Cache::put($cacheKey, $currentUserInfo);
        }

        return $this->paymentModeModel->query()->whereNull('country_id')
            ->when($currentUserInfo, function (Builder $builder) use ($currentUserInfo) {
                $country = Country::findByISO2($currentUserInfo->countryCode);
                $builder->orWhere('country_id', $country->id);
            })->get()->map(fn(PaymentMode $paymentMode) => [
                'id' => $paymentMode->id,
                'name' => $paymentMode->name,
            ]);

    }

    public function pay(User $user, int $merchantId, array $payload)
    {
        // TODO, Charge the payment method
        // TODO,  Save the form response
        $this->transactionService->processPayment($user, $merchantId, $payload);
    }

    public function getAllMerchants(?User $user): Collection
    {
        return $this->merchantModel->query()
            ->where('status', StatusUtils::ACTIVE)
            ->get()
            ->map(function (Merchant $merchant) {
                return MerchantDto::map($merchant);
            });
    }

    public function getAllPaymentTypes(?User $user, int $merchantId): Collection
    {
        return $this->paymentTypeModel->query()
            ->where('merchant_id', $merchantId)
            ->where('status', StatusUtils::ACTIVE)
            ->get()->map(function (PaymentType $paymentType) {
                return [
                    'id' => $paymentType->id,
                    'name' => $paymentType->name,
                ];
            });
    }
}
