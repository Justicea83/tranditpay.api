<?php

namespace App\Services\Merchant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IMerchantService
{
    public function setup(?User $user, array $payload);

    public function createMerchant(?User $user, array $payload): ?Model;

    public function getMerchants(User $user): LengthAwarePaginator;

    public function getPaymentTypes(User $user, int $merchantId): LengthAwarePaginator;

    public function getForm(User $user, int $merchantId, int $paymentTypeId): array;

    public function getPaymentModes(User $user): Collection;

    public function pay(User $user, int $merchantId, array $payload);
}
