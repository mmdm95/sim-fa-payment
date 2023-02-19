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
        $this->parameters['OrderId'] = $data['OrderId'] ?? null;
        $this->parameters['token'] = $data['token'] ?? null;
        $this->parameters['ResCode'] = $data['ResCode'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return $this->parameters['OrderId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getToken($prefer = null)
    {
        return $this->parameters['token'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return $this->parameters['ResCode'] ?? $prefer;
    }
}
