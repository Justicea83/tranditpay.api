<?php

namespace App\Entities\Response\Payments\Paystack;

use App\Entities\Response\Payments\Contracts\ShouldTransform;
use App\Entities\Response\Payments\PaymentResponse;
use App\Models\User;
use App\Utils\Payments\PaystackUtility;
use Symfony\Component\HttpFoundation\Response;

class PaystackResponse implements ShouldTransform
{
    public bool $status = false;
    public string $message;
    public array $data;

    public function isSuccessful(): bool
    {
        return $this->status;
    }

    public static function instance(): PaystackResponse
    {
        return new PaystackResponse();
    }

    /**
     * @param bool $status
     * @return PaystackResponse
     */
    public function setStatus(bool $status): PaystackResponse
    {
        $this->status = $status;
        return $this;
    }

    public function hasSuccessfulStatus(): bool
    {
        return $this->data['status'] === PaystackUtility::STATUS_SUCCESS;
    }

    /**
     * @param string $message
     * @return PaystackResponse
     */
    public function setMessage(string $message): PaystackResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param array $data
     * @return PaystackResponse
     */
    public function setData(array $data): PaystackResponse
    {
        $this->data = $data;
        return $this;
    }

    public function transformToPaymentResponse(User $user, bool $processed = false): PaymentResponse
    {
        $response = PaymentResponse::instance();

        if (!$this->isSuccessful()) {
            return $response->setCode(Response::HTTP_BAD_REQUEST);
        }

        $response = $response->setEmail($user->email)
            ->setMessage($this->message ?? '')
            ->setPaymentInfo($this->data ?? [])
            ->setCode(Response::HTTP_OK)
            ->setProvider(PayStackUtility::NAME)
            ->setReference($this->data['reference'] ?? '')
            ->setProcessed($this->data || $processed);

        if (in_array($this->data['status'], [PaystackUtility::PENDING_MESSAGE, PaystackUtility::STATUS_SENT_OTP, PaystackUtility::STATUS_PAY_OFFLINE])) {
            $response = $response->setMessage(PayStackUtility::PENDING_MESSAGE)->setProcessed(false);

            if ($this->data['status'] === PaystackUtility::STATUS_PAY_OFFLINE) {
                $response = $response->setMessage(PaystackUtility::STATUS_PAY_OFFLINE);
            }
        }

        return $response;
    }
}
