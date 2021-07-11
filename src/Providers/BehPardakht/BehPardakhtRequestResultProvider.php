<?php

namespace Sim\Payment\Providers\BehPardakht;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class BehPardakhtRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * BehPardakhtRequestResultProvider constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->parameters['RefId'] = isset($data['RefId']) ? $data['RefId'] : null;
        $this->parameters['ResCode'] = isset($data['ResCode']) ? $data['ResCode'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRefId($prefer = null)
    {
        return isset($this->parameters['RefId']) ? $this->parameters['RefId'] : $prefer;
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