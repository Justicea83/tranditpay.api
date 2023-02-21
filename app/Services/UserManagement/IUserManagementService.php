<?php

namespace App\Services\UserManagement;

use App\Models\Copilot\Copilot;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserManagementService
{
    public function createUser(array $payload, bool $resetPassword = false): Model;

    public function getCopilots(User $user, int $merchantId): LengthAwarePaginator;

    public function getUserByEmailOrPhone(string $searchValue): array;

    public function updateUser(User $user, int $id, array $payload);

    public function suspendUser(User $user, int $userId, array $payload);

    public function addCopilot(User $user, int $merchantId, array $payload): Copilot;

    public function removeCopilot(User $user, int $merchantId, int $userId);

    public function blockCopilot(User $user, int $merchantId, int $userId);

    public function suspendCopilot(User $user, int $merchantId, int $userId, array $payload);

    public function unBlockCopilot(User $user, int $merchantId, int $userId);

    public function unSuspendCopilot(User $user, int $merchantId, int $userId);
}
