<?php

namespace App\Services\Auth;

use App\Models\User;

interface IAuthService
{
    public function mobileAppLogin(array $data): array;

    public function getAuthUserProfile(User $user): array;

    public function mobileLogout(User $user);

    public function logoutOfAllDevices(User $user);

    public function sendForgotPasswordEmail(User $user);

    public function sendForgotPasswordEmailWithEmail(string $email);

    public function resetPassword(array $payload): string;

    public function loginWithRefreshToken(array $data): array;

    public function changePassword(User $user, string $password, bool $logout = false);

    public function sendOtp(string $phone): array;

    public function verifyOtp(string $phone, string $otp): array;

    public function loginWithOtp(array $payload): array;

    public function registerWithOtp(array $payload);

    public function pruneOtps();
}
