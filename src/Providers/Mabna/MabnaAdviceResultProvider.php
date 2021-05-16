<?php

namespace Sim\Payment\Providers\Mabna;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class MabnaAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * MabnaAdviceResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = $data['Status'] ?? null;
        $this->parameters['ReturnId'] = $data['ReturnId'] ?? null;
        $this->parameters['Message'] = $data['Message'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return $this->parameters['Status'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getReturnId($prefer = null)
    {
        return $this->parameters['ReturnId'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getMessage($prefer = null)
    {
        return $this->parameters['Message'] ?: $prefer;
    }
}
