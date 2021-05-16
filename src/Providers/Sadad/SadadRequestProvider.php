<?php

namespace Sim\Payment\Providers\Sadad;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class SadadRequestProvider extends AbstractParameterProvider
{
    /**
     * SadadParameterProvider constructor.
     */
    public function __construct()
    {
        $this->setLocalDateTime(date("m/d/Y g:i:s a"));
    }

    /**
     * @param $amount
     */
    public function setAmount($amount)
    {
        $this->parameters['Amount'] = $amount;
    }

    /**
     * @param $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->parameters['ReturnUrl'] = $returnUrl;
    }

    /**
     * @param $localDateTime
     */
    public function setLocalDateTime($localDateTime)
    {
        $this->parameters['LocalDateTime'] = $localDateTime;
    }

    /**
     * @param $orderId
     */
    public function setOrderId($orderId)
    {
        $this->parameters['OrderId'] = $orderId;
    }
}
