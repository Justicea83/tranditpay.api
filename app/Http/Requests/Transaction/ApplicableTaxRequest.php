<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class ApplicableTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'merchant_id' => 'required|exists:merchants,id',
            'payment_method' => 'required|exists:payment_modes,name',
            'amount' => 'nullable'
        ];
    }
}
