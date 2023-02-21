<?php

namespace App\Services\Merchant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface IMerchantService
{
    public function setup(?User $user, array $payload);

    public function createMerchant(?User $user, array $payload): ?Model;
}
