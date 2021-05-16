<?php

namespace Sim\Payment\Providers\IDPay;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class IDPayRequestProvider extends AbstractParameterProvider
{
    /**
     * @param $id
     * @return static
     */
    public function setOrderId($id)
    {
        $this->parameters['order_id'] = $id;
        return $this;
    }

    /**
     * @param $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->parameters['amount'] = $amount;
        return $this;
    }

    /**
     * @param $url
     * @return static
     */
    public function setCallbackUrl($url)
    {
        $this->parameters['callback'] = $url;
        return $this;
    }
}