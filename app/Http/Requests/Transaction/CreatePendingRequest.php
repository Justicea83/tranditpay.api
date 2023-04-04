<?php

namespace App\Http\Requests\Transaction;

use App\Entities\PendingRequests\PendingAction;
use App\Models\Form\FormField;
use App\Models\Payment\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use InvalidArgumentException;

class CreatePendingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->hasHeader('hash') && $this->hasHeader('salt');
    }


    public function rules(): array
    {
        return [
            'amount' => 'required|decimal:0,4|min:1',
            'merchant_id' => 'exists:merchants,id',
            'type' => ['required', Rule::in(PendingAction::TYPES)],
            'form' => [Rule::requiredIf(function () {
                return $this->get('form') === PendingAction::TYPE_FROM_FORM;
            })],
            'form.payment_type_id' => 'required_if:via_form,true|exists:payment_types,id',
            'form.responses' => 'required_if:via_form,true',
            'payment_info.method' => 'required|exists:payment_modes,name',
            'payment_info.mobile_money' => 'required_if:payment_info.method,mobile_money',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $type = $this->get('type');

        $validator->after(function (Validator $validator) use ($type) {
            switch ($type) {
                case PendingAction::TYPE_FROM_FORM:
                    $paymentTypeId = Arr::get($this->get('form'), 'payment_type_id');
                    $responses = Arr::get($this->get('form'), 'responses');
                    $submittedFieldIds = array_map(fn($value) => $value['form_field_id'], $responses);

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
                            $validator->errors()->add('form.responses.form_field_id', 'Some required fields are missing');
                            break;
                        }
                    }

                    // Check the responses submitted from the form
                    $allFormFields = FormField::query()
                        ->whereIn('form_section_id', $sectionIds)
                        ->get();

                    /** @var FormField $formField */
                    foreach ($allFormFields as $formField) {
                        if (in_array($formField->formFieldType->name, ['dropdown', 'multiple_choice'])) {
                            $fieldResponse = $this->getFieldResponse($responses, $formField->id);
                            if ($fieldResponse) {
                                $fieldResponseValue = $fieldResponse['value'];
                                $possibleResponses = $formField->options()->pluck('id')->toArray();
                                if (!in_array($fieldResponseValue, $possibleResponses)) {
                                    $validator->errors()->add('form.responses.value', 'Invalid response provided');
                                }
                            }
                        }
                    }
                    break;
                default:
                    throw new InvalidArgumentException('Invalid type provided');
            }
        });

    }

    private function getFieldResponse(array $responses, int $fieldId)
    {
        return Arr::first($responses, function ($value) use ($fieldId) {
            return $value['form_field_id'] == $fieldId;
        });
    }
}
