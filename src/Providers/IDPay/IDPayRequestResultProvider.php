<?php

namespace Sim\Payment\Providers\IDPay;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IDPayRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * IDPayResultProvider constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->parameters['id'] = isset($data['id']) ? $data['id'] : null;
        $this->parameters['link'] = isset($data['link']) ? $data['link'] : null;
        $this->parameters['error_code'] = isset($data['error_code']) ? $data['error_code'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getId($prefer = null)
    {
        return isset($this->parameters['id']) ? $this->parameters['id'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getLink($prefer = null)
    {
        return isset($this->parameters['link']) ? $this->parameters['link'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getErrorCode($prefer = null)
    {
        return isset($this->parameters['error_code']) ? $this->parameters['error_code'] : $prefer;
    }
}
