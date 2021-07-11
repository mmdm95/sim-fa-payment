<?php

namespace Sim\Payment\Providers\BehPardakht;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class BehPardakhtSettleResultProvider extends AbstractBaseParameterProvider
{
    /**
     * BehPardakhtSettleResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['return'] = isset($data['return']) ? $data['return'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getReturn($prefer = null)
    {
        return isset($this->parameters['return']) ? $this->parameters['return'] : $prefer;
    }
}
