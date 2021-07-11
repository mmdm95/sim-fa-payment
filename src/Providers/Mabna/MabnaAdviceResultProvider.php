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
        $this->parameters['Status'] = isset($data['Status']) ? $data['Status'] : null;
        $this->parameters['ReturnId'] = isset($data['ReturnId']) ? $data['ReturnId'] : null;
        $this->parameters['Message'] = isset($data['Message']) ? $data['Message'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return isset($this->parameters['Status']) ? $this->parameters['Status'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getReturnId($prefer = null)
    {
        return isset($this->parameters['ReturnId']) ? $this->parameters['ReturnId'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getMessage($prefer = null)
    {
        return isset($this->parameters['Message']) ? $this->parameters['Message'] : $prefer;
    }
}
