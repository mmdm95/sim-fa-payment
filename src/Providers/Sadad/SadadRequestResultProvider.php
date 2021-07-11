<?php

namespace Sim\Payment\Providers\Sadad;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class SadadRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * SadadRequestResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['ResCode'] = isset($data['ResCode']) ? $data['ResCode'] : null;
        $this->parameters['Token'] = isset($data['Token']) ? $data['Token'] : null;
        $this->parameters['Description'] = isset($data['Description']) ? $data['Description'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return isset($this->parameters['ResCode']) ? $this->parameters['ResCode'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getToken($prefer = null)
    {
        return isset($this->parameters['Token']) ? $this->parameters['Token'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDescription($prefer = null)
    {
        return isset($this->parameters['Description']) ? $this->parameters['Description'] : $prefer;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://sadad.shaparak.ir/VPG/Purchase?Token=' . (string)$this->getToken();
    }
}
