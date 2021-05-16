<?php

namespace Sim\Payment\Providers\IDPay;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IDPayAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * IDPayAdviceResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['status'] = $data['status'] ?? null;
        $this->parameters['track_id'] = $data['payment']['track_id'] ?? null;
        $this->parameters['error_code'] = $data['error_code'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return $this->parameters['status'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTrackId($prefer = null)
    {
        return $this->parameters['track_id'] ?: $prefer;
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
