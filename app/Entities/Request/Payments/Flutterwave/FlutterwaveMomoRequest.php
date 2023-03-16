<?php

namespace App\Entities\Request\Payments\Flutterwave;

class FlutterwaveMomoRequest extends FlutterwaveRequest
{
    public string $network;

    public static function instance(): FlutterwaveMomoRequest
    {
        return new FlutterwaveMomoRequest();
    }

    /**
     * @param string $network
     * @return FlutterwaveMomoRequest
     */
    public function setNetwork(string $network): FlutterwaveMomoRequest
    {
        $this->network = $network;
        return $this;
    }
}
