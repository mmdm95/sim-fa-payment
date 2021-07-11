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
        $this->parameters['OrderId'] = isset($data['OrderId']) ? $data['OrderId'] : null;
        $this->parameters['token'] = isset($data['token']) ? $data['token'] : null;
        $this->parameters['ResCode'] = isset($data['ResCode']) ? $data['ResCode'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return isset($this->parameters['OrderId']) ? $this->parameters['OrderId'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getToken($prefer = null)
    {
        return isset($this->parameters['token']) ? $this->parameters['token'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return isset($this->parameters['ResCode']) ? $this->parameters['ResCode'] : $prefer;
    }
}
