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
        $this->parameters['status'] = isset($data['status']) ? $data['status'] : null;
        $this->parameters['track_id'] = isset($data['payment']['track_id']) ? $data['payment']['track_id'] : null;
        $this->parameters['error_code'] = isset($data['error_code']) ? $data['error_code'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return isset($this->parameters['status']) ? $this->parameters['status'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTrackId($prefer = null)
    {
        return isset($this->parameters['track_id']) ? $this->parameters['track_id'] : $prefer;
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
