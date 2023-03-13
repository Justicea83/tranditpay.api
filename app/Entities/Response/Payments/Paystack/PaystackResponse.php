<?php

namespace App\Entities\Response\Payments\Paystack;

use App\Entities\Response\Payments\PaymentResponse;
use App\Models\User;
use App\Utils\Payments\PaystackUtility;
use Symfony\Component\HttpFoundation\Response;

class PaystackResponse
{
    public bool $status;
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

    public function transformToCollectionResponse(User $user, string $ref, bool $processed): PaymentResponse
    {
        return PaymentResponse::instance()
            ->setEmail($user->email)
            ->setMessage($this->message)
            ->setPaymentInfo($this->data)
            ->setCode($this->isSuccessful() ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST)
            ->setProvider(PayStackUtility::NAME)
            ->setReference($ref)
            ->setData($this->data)
            ->setProcess($this->data != null || $processed);
    }
}
