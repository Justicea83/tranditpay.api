<?php

namespace App\Http\Requests\Webhooks\Payments;

use Illuminate\Foundation\Http\FormRequest;

class PaystackEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->hasHeader('x-paystack-signature') &&
            $this->header('x-paystack-signature') === hash_hmac('sha512', @file_get_contents("php://input"), config('paystack.secretKey'));
    }

    public function rules(): array
    {
        return [
        ];
    }
}
