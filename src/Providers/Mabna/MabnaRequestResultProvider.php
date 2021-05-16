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
        $this->parameters['Status'] = $data['Status'] ?? null;
        $this->parameters['AccessToken'] = $data['AccessToken'] ?? null;
        $this->parameters['Url'] = $data['Url'] ?? null;
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
    public function getAccessToken($prefer = null)
    {
        return $this->parameters['AccessToken'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getUrl($prefer = null)
    {
        return $this->parameters['Url'] ?: $prefer;
    }
}
