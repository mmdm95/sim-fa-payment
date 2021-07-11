<?php

namespace Sim\Payment\Providers\Zarinpal;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class ZarinpalRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * ZarinpalResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = isset($data['Status']) ? $data['Status'] : null;
        $this->parameters['Authority'] = isset($data['Authority']) ? $data['Authority'] : null;
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
    public function getAuthority($prefer = null)
    {
        return isset($this->parameters['Authority']) ? $this->parameters['Authority'] : $prefer;
    }
}
