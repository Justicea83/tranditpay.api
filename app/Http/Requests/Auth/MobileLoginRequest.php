<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class MobileLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            /** @var User $user */
            $user = User::query()->where('email', $this->request->get('email'))->first();
            if ($user == null) {
                $this->addUserEmailValidationError($validator);
            } else {
                if (!Hash::check($this->request->get('password'), $user->password))
                    $this->addUserEmailValidationError($validator);
            }

        });

    }

    private function addUserEmailValidationError(Validator $validator)
    {
        $validator->errors()->add('email', 'your credentials do not match our records');
    }
}
