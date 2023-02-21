<?php

namespace App\Services\Merchant;

use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Services\UserManagement\IUserManagementService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchantService implements IMerchantService
{
    private IUserManagementService $userManagementService;
    private Merchant $merchantModel;

    function __construct(
        IUserManagementService $userManagementService,
        Merchant               $merchantModel
    )
    {
        $this->userManagementService = $userManagementService;
        $this->merchantModel = $merchantModel;
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
            // create merchant
            /** @var Merchant $createdMerchant */
            $this->createMerchant($user, $merchant);

            $this->userManagementService->createUser($userData);

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

}
