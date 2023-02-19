<?php

namespace Sim\Payment\Providers\Zarinpal;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class ZarinpalHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * ZarinpalHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = $data['Status'] ?? null;
        $this->parameters['Authority'] = $data['Authority'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return $this->parameters['Status'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAuthority($prefer = null)
    {
        return $this->parameters['Authority'] ?? $prefer;
    }
}
