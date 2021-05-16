<?php

namespace Sim\Payment\Providers\Zarinpal;

use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;

class ZarinpalAdviceProvider extends AbstractAdviceParameterProvider
{
    /**
     * @param int $amount
     * @return static
     */
    public function setAmount(int $amount)
    {
        $this->parameters['Amount'] = $amount;
        return $this;
    }
}
