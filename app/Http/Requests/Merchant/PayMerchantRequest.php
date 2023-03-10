<?php

namespace App\Http\Requests\Merchant;

use App\Models\Form\FormField;
use App\Models\Form\FormFieldOption;
use App\Models\Payment\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class PayMerchantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->hasHeader('hash') && $this->hasHeader('salt');
    }

    public function rules(): array
    {
        return [
            'via_form' => 'required|boolean',
            'form' => 'required_if:via_form,true',
            'form.payment_type_id' => 'required_if:via_form,true|exists:payment_types,id',
            'form.values' => 'required_if:via_form,true',
            'payment_info.method' => 'required|exists:payment_modes,name',
            'payment_info.card' => 'required_if:payment_info.method,card',
            'payment_info.mobile_money' => 'required_if:payment_info.method,mobile_money',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $merchant = $this->route('id');
        $viaForm = $this->get('via_form');

        $validator->after(function (Validator $validator) use ($merchant, $viaForm) {
            if ($viaForm) {
                $paymentTypeId = Arr::get($this->get('form'), 'payment_type_id');
                $values = Arr::get($this->get('form'), 'values');
                $submittedFieldIds = array_map(fn($value) => $value['form_field_id'], $values);

                /** @var PaymentType $paymentType */
                $paymentType = PaymentType::query()->find($paymentTypeId);
                $form = $paymentType->form;

                $sectionIds = $form->sections()->pluck('id');
                $requiredFieldsIds = FormField::query()
                    ->whereIn('form_section_id', $sectionIds)
                    ->where('required', true)
                    ->pluck('id')
                    ->toArray();

                // Check for all the required fields
                foreach ($requiredFieldsIds as $requiredFieldsId) {
                    if (!in_array($requiredFieldsId, $submittedFieldIds)) {
                        $validator->errors()->add('form.values.form_field_id', 'Some required fields are missing');
                        break;
                    }
                }

                // Check the values submitted from the form
                $allFormFields = FormField::query()
                    ->whereIn('form_section_id', $sectionIds)
                    ->get();

                /** @var FormField $formField */
                foreach ($allFormFields as $formField) {
                    if (in_array($formField->formFieldType->name, ['dropdown', 'multiple_choice'])) {
                        $fieldResponse = $this->getFieldResponse($values, $formField->id);
                        if ($fieldResponse) {
                            $fieldResponseValue = $fieldResponse['value'];
                            $possibleResponses = $formField->options()->pluck('id')->toArray();
                            if (!in_array($fieldResponseValue, $possibleResponses)) {
                                $validator->errors()->add('form.values.value', 'Invalid response provided');
                            }
                        }
                    }
                }
            }
        });

    }

    private function getFieldResponse(array $values, int $fieldId)
    {
        return Arr::first($values, function ($value) use ($fieldId) {
            return $value['form_field_id'] == $fieldId;
        });
    }
}
