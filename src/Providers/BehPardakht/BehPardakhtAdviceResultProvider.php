<?php

namespace Sim\Payment\Providers\BehPardakht;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class BehPardakhtAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * BehPardakhtAdviceResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['return'] = $data['return'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getReturn($prefer = null)
    {
        return $this->parameters['return'] ?: $prefer;
    }
}
