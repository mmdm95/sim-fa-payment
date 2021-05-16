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
}
