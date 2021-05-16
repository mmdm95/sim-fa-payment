<?php

namespace Sim\Payment\Providers\Zarinpal;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class ZarinpalAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * ZarinpalResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = $data['Status'] ?? null;
        $this->parameters['RefID'] = $data['RefID'] ?? null;
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
    public function getRefID($prefer = null)
    {
        return $this->parameters['RefID'] ?: $prefer;
    }
}
