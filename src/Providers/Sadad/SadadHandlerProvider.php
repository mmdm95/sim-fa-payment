<?php

namespace Sim\Payment\Providers\Sadad;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class SadadHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * SadadHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['OrderId'] = $data['OrderId'];
        $this->parameters['token'] = $data['token'];
        $this->parameters['ResCode'] = $data['ResCode'];
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return $this->parameters['OrderId'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getToken($prefer = null)
    {
        return $this->parameters['token'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return $this->parameters['ResCode'] ?: $prefer;
    }
}
