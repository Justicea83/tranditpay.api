<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateMerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //merchant info
            'merchant.name' => 'required',
            'merchant.primary_email' => 'email:rfc,dns|unique:merchants,primary_email|nullable',
            'merchant.primary_phone' => 'nullable',
            'merchant.country_id' => 'required',
            'merchant.website' => 'url|nullable',
            'merchant.about' => 'string|nullable',

            //user info
            'user.first_name' => 'required',
            'user.last_name' => 'required',
            'user.email' => 'email:rfc,dns|required|unique:users,email',
            'user.phone' => 'required|unique:users,phone',
        ];
    }
}
