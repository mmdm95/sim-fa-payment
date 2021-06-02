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
     * @return static
     */
    public function setAmount($amount)
    {
        $this->parameters['Amount'] = $amount;
        return $this;
    }

    /**
     * @param $returnUrl
     * @return static
     */
    public function setReturnUrl($returnUrl)
    {
        $this->parameters['ReturnUrl'] = $returnUrl;
        return $this;
    }

    /**
     * @param $localDateTime
     * @return static
     */
    public function setLocalDateTime($localDateTime)
    {
        $this->parameters['LocalDateTime'] = $localDateTime;
        return $this;
    }

    /**
     * @param $orderId
     * @return static
     */
    public function setOrderId($orderId)
    {
        $this->parameters['OrderId'] = $orderId;
        return $this;
    }

    /**
     * @param string $additionalData
     * @return static
     */
    public function setAdditionalData(string $additionalData)
    {
        $this->parameters['AdditionalData'] = $additionalData;
        return $this;
    }

    /**
     * @param $userId
     * @return static
     */
    public function setUserId($userId)
    {
        $this->parameters['UserId'] = $userId;
        return $this;
    }

    /**
     * @param string $applicationName
     */
    public function setApplicationName(string $applicationName)
    {
        $this->parameters['ApplicationName'] = $applicationName;
    }
}
