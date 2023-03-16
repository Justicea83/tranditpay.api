<?php

namespace App\Entities\Response\Payments;

class ValidateOtpResponse
{
    public string $status;
    public string $message;

    public static function instance(): ValidateOtpResponse
    {
        return new ValidateOtpResponse();
    }

    /**
     * @param string $status
     * @return ValidateOtpResponse
     */
    public function setStatus(string $status): ValidateOtpResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $message
     * @return ValidateOtpResponse
     */
    public function setMessage(string $message): ValidateOtpResponse
    {
        $this->message = $message;
        return $this;
    }
}
