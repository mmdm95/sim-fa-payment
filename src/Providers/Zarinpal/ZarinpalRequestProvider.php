<?php

namespace Sim\Payment\Providers\Zarinpal;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class ZarinpalRequestProvider extends AbstractParameterProvider
{
    /**
     * @param $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->parameters['Amount'] = $amount;
        return $this;
    }

    /**
     * @param $url
     * @return static
     */
    public function setCallbackUrl($url)
    {
        $this->parameters['CallbackURL'] = $url;
        return $this;
    }

    /**
     * @param $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->parameters['Description'] = $description;
        return $this;
    }
}
