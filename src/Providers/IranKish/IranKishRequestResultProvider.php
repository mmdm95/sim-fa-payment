<?php

namespace Sim\Payment\Providers\IranKish;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IranKishRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * IranKishRequestResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['responseCode'] = $data['responseCode'] ?? null;
        $this->parameters['token'] = $data['result']['token'] ?? null;
        $this->parameters['description'] = $data['description'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResponseCode($prefer = null)
    {
        return $this->parameters['responseCode'] ?? $prefer;
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
    public function getDescription($prefer = null)
    {
        return $this->parameters['description'] ?? $prefer;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://ikc.shaparak.ir/iuiv3/IPG/Index/';
    }
}
