<?php

namespace App\Services\UserManagement;

use App\Models\Copilot\Copilot;
use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Services\Auth\IAuthService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class UserManagementService implements IUserManagementService
{
    private User $userModel;
    private Copilot $copilotModel;
    private IAuthService $authService;
    private Merchant $merchantModel;

    function __construct(
        User     $userModel, IAuthService $authService, Copilot $copilotModel,
        Merchant $merchantModel
    )
    {
        $this->userModel = $userModel;
        $this->authService = $authService;
        $this->copilotModel = $copilotModel;
        $this->merchantModel = $merchantModel;
    }

    public function createUser(array $payload, bool $resetPassword = false): Model
    {
        $forceSendPasswordResetEmail = !isset($payload['password']);
        $payload['password'] = !$forceSendPasswordResetEmail ? bcrypt($payload['password']) : bcrypt('password123@');
        /** @var User $user */
        $user = $this->userModel->query()->create($payload);
        if (($resetPassword || $forceSendPasswordResetEmail) && $user->email) {
            $this->authService->sendForgotPasswordEmail($user);
        }
        return $user;
    }

    // add middleware to check access
    public function getCopilots(User $user, int $merchantId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        /** @var Merchant $merchant */
        $merchant = $this->merchantModel->query()->find($merchantId);
        if (!$merchant) {
            throw new ModelNotFoundException();
        }
        $copilots = $this->copilotModel->query()->with(['pilot', 'restrictions'])->where('merchant_id', $merchantId)
            ->paginate($pageSize, ['*'], 'page', $page);

        $copilots->getCollection()->transform(function (Copilot $copilot) {
            $formattedUser = $this->authService->getAuthUserProfile($copilot->pilot);
            $formattedUser['restrictions'] = $copilot->restrictions->map(fn($item) => $item->name);
            return $formattedUser;
        });
        return $copilots;
    }

    public function getUserByEmailOrPhone(string $searchValue): array
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email', $searchValue)->orWhere('phone', $searchValue)->first();
        if (!$user) {
            return [];
        }

        return $this->authService->getAuthUserProfile($user);
    }

    public function updateUser(User $user, int $id, array $payload)
    {
        $user = $this->findUser($id);
        //update only the first name and last name fields
        $firstName = Arr::get($payload, 'first_name');
        $lastName = Arr::get($payload, 'last_name');
        if ($firstName != null) $user->first_name = $firstName;
        if ($lastName != null) $user->last_name = $lastName;
        if ($user->isDirty()) $user->save();
    }

    public function suspendUser(User $user, int $userId, array $payload)
    {
        $user = $this->findUser($userId);
        $suspendUntil = Carbon::parse($payload['suspended_until']);
        $user->suspended_until = $suspendUntil->timestamp;
        $user->save();
    }

    private function findUser(int $id): User
    {
        /** @var User $user */
        $user = $this->userModel->query()->find($id);
        if ($user == null) throw new InvalidArgumentException("user not found");
        return $user;
    }

    public function addCopilot(User $user, int $merchantId, array $payload) : Copilot
    {
        /** @var Copilot $copilot */
        $copilot = $this->copilotModel->query()->create([
            'merchant_id' => $merchantId,
            'pilot' => $payload['user_id'],
        ]);
        foreach ($payload['restrictions'] as $restriction) {
            $copilot->restrictions()->create([
                'restriction_id' => $restriction
            ]);
        }

        return $copilot;

    }

    public function removeCopilot(User $user, int $merchantId, int $userId)
    {
        $copilot = Copilot::findByMerchantAndPilot($merchantId, $userId);
        $copilot?->delete();
    }

    public function blockCopilot(User $user, int $merchantId, int $userId)
    {
        $copilot = Copilot::findByMerchantAndPilot($merchantId, $userId);
        $copilot->blocked = true;
        $copilot->save();
    }

    public function suspendCopilot(User $user, int $merchantId, int $userId, array $payload)
    {
        $copilot = Copilot::findByMerchantAndPilot($merchantId, $userId);
        $suspendUntil = Carbon::parse($payload['suspended_until']);
        $copilot->suspended_until = $suspendUntil->timestamp;
        $copilot->save();
    }

    public function unBlockCopilot(User $user, int $merchantId, int $userId)
    {
        $copilot = Copilot::findByMerchantAndPilot($merchantId, $userId);
        $copilot->blocked = false;
        $copilot->save();
    }

    public function unSuspendCopilot(User $user, int $merchantId, int $userId)
    {
        $copilot = Copilot::findByMerchantAndPilot($merchantId, $userId);
        $copilot->suspended_until = null;
        $copilot->save();
    }
}
