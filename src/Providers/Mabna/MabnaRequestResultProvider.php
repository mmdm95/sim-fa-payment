<?php

namespace Sim\Payment\Providers\Mabna;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class MabnaRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * MabnaRequestResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = isset($data['Status']) ? $data['Status'] : null;
        $this->parameters['AccessToken'] = isset($data['AccessToken']) ? $data['AccessToken'] : null;
        $this->parameters['Url'] = isset($data['Url']) ? $data['Url'] : null;
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
    public function getAccessToken($prefer = null)
    {
        return isset($this->parameters['AccessToken']) ? $this->parameters['AccessToken'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getUrl($prefer = null)
    {
        return isset($this->parameters['Url']) ? $this->parameters['Url'] : $prefer;
    }
}
