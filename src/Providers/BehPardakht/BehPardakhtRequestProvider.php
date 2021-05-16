<?php

namespace Sim\Payment\Providers\BehPardakht;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class BehPardakhtRequestProvider extends AbstractParameterProvider
{
    /**
     * BehPardakhtParameterProvider constructor.
     */
    public function __construct()
    {
        $this->setLocalDate((string)date('Ymd'));
        $this->setLocalTime((string)date('Gis'));
    }

    /**
     * @param $order_id
     * @return static
     */
    public function setOrderId($order_id)
    {
        $this->parameters['orderId'] = $order_id;
        return $this;
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
     * @param $time
     * @return static
     */
    public function setLocalDate($time)
    {
        $this->parameters['localDate'] = $time;
        return $this;
    }

    /**
     * @param $time
     * @return static
     */
    public function setLocalTime($time)
    {
        $this->parameters['localTime'] = $time;
        return $this;
    }

    /**
     * @param $url
     * @return static
     */
    public function setCallbackUrl($url)
    {
        $this->parameters['callBackUrl'] = $url;
        return $this;
    }

    /**
     * @param $additional
     * @return static
     */
    public function setAdditionalData($additional)
    {
        $this->parameters['additionalData'] = $additional;
        return $this;
    }

    /**
     * @param $payer_id
     * @return static
     */
    public function setPayerId($payer_id)
    {
        $this->parameters['payerId'] = $payer_id;
        return $this;
    }
}