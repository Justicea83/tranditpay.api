<?php

namespace App\Entities\PendingRequests;

class PendingPayFromForm extends PendingPaymentAction
{
    public string $payment_type_id;
    public array $responses;

    /**
     * @param string $payment_type_id
     * @return PendingPayFromForm
     */
    public function setPaymentTypeId(string $payment_type_id): PendingPayFromForm
    {
        $this->payment_type_id = $payment_type_id;
        return $this;
    }

    /**
     * @param array $responses
     * @return PendingPayFromForm
     */
    public function setResponses(array $responses): PendingPayFromForm
    {
        $this->responses = $responses;
        return $this;
    }

    public static function instance(): PendingPayFromForm
    {
        return new PendingPayFromForm();
    }
}
