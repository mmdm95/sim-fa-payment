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
        $this->parameters['id'] = $data['id'] ?? null;
        $this->parameters['link'] = $data['link'] ?? null;
        $this->parameters['error_code'] = $data['error_code'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getId($prefer = null)
    {
        return $this->parameters['id'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getLink($prefer = null)
    {
        return $this->parameters['link'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getErrorCode($prefer = null)
    {
        return $this->parameters['error_code'] ?: $prefer;
    }
}
