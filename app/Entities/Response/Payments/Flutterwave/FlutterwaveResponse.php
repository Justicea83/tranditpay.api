<?php

namespace App\Entities\Response\Payments\Flutterwave;

use App\Entities\Response\Payments\PaymentResponse;
use App\Entities\Response\Payments\ValidateOtpResponse;
use App\Models\User;
use App\Utils\Payments\FlutterwaveUtility;
use Symfony\Component\HttpFoundation\Response;

class FlutterwaveResponse
{
    public string $status;
    public array $meta;
    public string $message;
    public array $data;

    public function isSuccessful(): bool
    {
        return $this->status === FlutterwaveUtility::SUCCESS_RESPONSE;
    }

    public static function instance(): FlutterwaveResponse
    {
        return new FlutterwaveResponse();
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return FlutterwaveResponse
     */
    public function setStatus(string $status): FlutterwaveResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param array $meta
     * @return FlutterwaveResponse
     */
    public function setMeta(array $meta): FlutterwaveResponse
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @param string $message
     * @return FlutterwaveResponse
     */
    public function setMessage(string $message): FlutterwaveResponse
    {
        $this->message = $message;
        return $this;
    }

    public function transformToPaymentResponse(User $user, bool $processed = false): PaymentResponse
    {
        $response = PaymentResponse::instance();

        if (!$this->isSuccessful()) {
            return $response->setCode(Response::HTTP_BAD_REQUEST);
        }

        return $response->setEmail($user->email)
            ->setMessage($this->message)
            ->setPaymentInfo($this->data)
            ->setMeta($this->meta)
            ->setCode(Response::HTTP_OK)
            ->setProvider(FlutterwaveUtility::NAME)
            ->setReference($this->data['tx_ref'] ?? '')
            ->setProcessed($this->data != null || $processed);
    }

    public function transformToValidateOtpResponse(): ValidateOtpResponse
    {
        return ValidateOtpResponse::instance()->setMessage($this->message)->setStatus($this->status);
    }

    /**
     * @param array $data
     * @return FlutterwaveResponse
     */
    public function setData(array $data): FlutterwaveResponse
    {
        $this->data = $data;
        return $this;
    }
}

