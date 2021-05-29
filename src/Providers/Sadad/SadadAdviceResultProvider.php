<?php

namespace Sim\Payment\Providers\Sadad;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class SadadAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * SadadResultProvider constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->parameters['ResCode'] = $data['ResCode'] ?? null;
        $this->parameters['RetrivalRefNo'] = $data['RetrivalRefNo'] ?? null;
        $this->parameters['SystemTraceNo'] = $data['SystemTraceNo'] ?? null;
        $this->parameters['Amount'] = $data['Amount'] ?? null;
        $this->parameters['Description'] = $data['Description'] ?? null;
        $this->parameters['OrderId'] = $data['OrderId'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return $this->parameters['ResCode'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRetrivalRefNo($prefer = null)
    {
        return $this->parameters['RetrivalRefNo'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSystemTraceNo($prefer = null)
    {
        return $this->parameters['SystemTraceNo'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return $this->parameters['Amount'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDescription($prefer = null)
    {
        return $this->parameters['Description'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return $this->parameters['OrderId'] ?: $prefer;
    }
}
