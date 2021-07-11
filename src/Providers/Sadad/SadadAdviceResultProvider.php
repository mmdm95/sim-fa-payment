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
        $this->parameters['ResCode'] = isset($data['ResCode']) ? $data['ResCode'] : null;
        $this->parameters['RetrivalRefNo'] = isset($data['RetrivalRefNo']) ? $data['RetrivalRefNo'] : null;
        $this->parameters['SystemTraceNo'] = isset($data['SystemTraceNo']) ? $data['SystemTraceNo'] : null;
        $this->parameters['Amount'] = isset($data['Amount']) ? $data['Amount'] : null;
        $this->parameters['Description'] = isset($data['Description']) ? $data['Description'] : null;
        $this->parameters['OrderId'] = isset($data['OrderId']) ? $data['OrderId'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return isset($this->parameters['ResCode']) ? $this->parameters['ResCode'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRetrivalRefNo($prefer = null)
    {
        return isset($this->parameters['RetrivalRefNo']) ? $this->parameters['RetrivalRefNo'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSystemTraceNo($prefer = null)
    {
        return isset($this->parameters['SystemTraceNo']) ? $this->parameters['SystemTraceNo'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return isset($this->parameters['Amount']) ? $this->parameters['Amount'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDescription($prefer = null)
    {
        return isset($this->parameters['Description']) ? $this->parameters['Description'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return isset($this->parameters['OrderId']) ? $this->parameters['OrderId'] : $prefer;
    }
}
