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
        $this->parameters['RefId'] = $data['RefId'] ?? null;
        $this->parameters['ResCode'] = $data['ResCode'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRefId($prefer = null)
    {
        return $this->parameters['RefId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return $this->parameters['ResCode'] ?? $prefer;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat';
    }
}