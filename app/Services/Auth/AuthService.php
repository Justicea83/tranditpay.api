<?php

namespace App\Services\Auth;

use App\Mail\Auth\ForgotPasswordMail;
use App\Mail\Auth\NewUserPasswordChange;
use App\Models\Auth\Otp;
use App\Models\User;
use App\Utils\AppUtils;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class AuthService implements IAuthService
{
    function __construct(private readonly User $userModel, private readonly Otp $otpModel)
    {
    }

    public function mobileAppLogin(array $data): array
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email', $data['email'])->first();

        if (!$user) return [];

        $response = Http::asForm()->post('nginx/oauth/token', [
            'grant_type' => 'password',
            'client_id' => config('env.auth.pa_client_id'),
            'client_secret' => config('env.auth.pa_client_secret'),
            'username' => $data['email'],
            'password' => $data['password'],
            'scope' => '',
        ]);
        $responseInfo = $response->json();
        if ($response->ok())
            $responseInfo['user'] = $this->getAuthUserProfile($user);
        return $responseInfo;
    }

    public function mobileLogout(User $user)
    {
        $user->token()->revoke();
    }

    public function logoutOfAllDevices(User $user)
    {
        $user->tokens()->delete();
    }

    public function sendForgotPasswordEmail(?User $user)
    {
        if ($user == null) return;
        $token = Password::createToken($user);
        Mail::to($user)->queue(new NewUserPasswordChange($user, "$token?email=$user->email"));
    }

    public function sendForgotPasswordEmailWithEmail(string $email)
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email', $email)->first();
        if (!$user) return;
        $token = Password::createToken($user);
        Mail::to($user)->queue(new ForgotPasswordMail($user, "$token?email=$user->email"));
    }

    public function resetPassword(array $payload): string
    {
        ['token' => $token, 'email' => $email, 'password' => $password] = $payload;

        return Password::reset(
            [
                'email' => $email,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();

                //TODO send welcome emails here
            }
        );
    }


    public function getAuthUserProfile(User $user): array
    {
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'email_verified' => $user->email_verified_at != null,
            'phone_verified' => $user->phone_verified_at != null,
            'joined' => $user->created_at,
        ];
    }

    public function loginWithRefreshToken(array $data): array
    {
        $response = Http::asForm()->acceptJson()
            ->post('nginx/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $data['refresh_token'],
                'client_id' => config('env.auth.pg_client_id'),
                'client_secret' => config('env.auth.pg_client_secret'),
                'scope' => '',
            ]);
        return $response->json();
    }

    public function changePassword(User $user, string $password, bool $logout = false)
    {
        $user->password = bcrypt($password);
        if ($user->isDirty()) {
            $user->save();

            // Logout of other devices except the current device
            if ($logout) {
                $user->tokens()
                    ->where('id', '<>', $user->token()->id)
                    ->delete();
            }
        }
    }

    public function sendOtp(string $phone): array
    {
        $code = rand(1000, 9999);
        $response = [];

        $this->otpModel->query()->create([
            'phone' => $phone,
            'otp_code' => $code,
            'expires_at' => now()->addMinutes(30)->toDayDateTimeString()
        ]);

        if (app()->environment(['local'])) {
            $response['otp_code'] = $code;
        }

        return $response;
    }

    public function verifyOtp(string $phone, string $otp): array
    {
        /** @var Otp $otp */
        $otp = $this->otpModel->query()->where('otp_code', $otp)->where('phone', $phone)->latest()->first();
        $response = [
            'verified' => false,
            'has_account' => false
        ];

        if (!$otp) {
            return $response;
        }

        if (now()->lessThan(Carbon::parse($otp->expires_at))) {
            $response['verified'] = true;
        }

        if ($this->userModel->query()->where('phone', $phone)->orWhere('email', $phone)->exists()) {
            $response['has_account'] = true;
        }

        return $response;
    }

    public function loginWithOtp(array $payload): array
    {
        [
            'phone' => $phone,
            'otp_code' => $otpCode
        ] = $payload;
        [
            'verified' => $verified,
            'has_account' => $hasAccount
        ] = $this->verifyOtp($phone, $otpCode);

        $response = [
            'token' => null
        ];

        if ($verified && $hasAccount) {
            /** @var User $user */
            $user = $this->userModel->query()->where('phone', $phone)->orWhere('email', $phone)->first();

            if (!$user->phone_verified_at) {
                $user->phone_verified_at = now()->toDateTimeString();
                $user->save();
            }

            $response['token'] = $user->createToken('Login')->accessToken;
        }

        return $response;
    }

    public function registerWithOtp(array $payload): array
    {
        $response = [
            'token' => null
        ];

        @[
            'phone' => $phone,
            'otp_code' => $otpCode,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName
        ] = $payload;

        try {
            /** @var User $user */
            $user = $this->userModel->query()->create([
                'phone' => $phone,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => bcrypt(AppUtils::getToken(16)) // Set a random password which can be reset later
            ]);
        } catch (QueryException $exception) {
            if (Str::contains($exception->getMessage(), 'Unique violation')) {
                return $this->loginWithOtp($payload);
            }
        }


        [
            'verified' => $verified,
        ] = $this->verifyOtp($phone, $otpCode);

        // Only log the person in if the phone is verified
        if ($verified) {
            if (!$user->phone_verified_at) {
                $user->phone_verified_at = now()->toDateTimeString();
                $user->save();
            }
            $response['token'] = $user->createToken('Login')->accessToken;
        }

        return $response;
    }

    #[NoReturn] public function pruneOtps(): void
    {
        $date = now()->format('Y-m-d');
        $time = now()->toTimeString();
        $this->otpModel->query()
            ->whereDate('created_at', '>=', $date)
            ->whereTime('expires_at', '<', $time)
            ->delete();
    }
}
