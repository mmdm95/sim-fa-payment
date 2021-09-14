<?php

namespace Sim\Payment\Providers\TAP\Payment;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class TapAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * TapAdviceResultProvider constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->parameters['Status'] = isset($data['ConfirmPaymentResult']['Status']) ? $data['ConfirmPaymentResult']['Status'] : null;
        $this->parameters['Message'] = isset($data['ConfirmPaymentResult']['Message']) ? $data['ConfirmPaymentResult']['Message'] : null;
        $this->parameters['CardNumberMasked'] = isset($data['ConfirmPaymentResult']['CardNumberMasked']) ? $data['ConfirmPaymentResult']['CardNumberMasked'] : null;
        $this->parameters['Token'] = isset($data['ConfirmPaymentResult']['Token']) ? $data['ConfirmPaymentResult']['Token'] : null;
        $this->parameters['RRN'] = isset($data['ConfirmPaymentResult']['RRN']) ? $data['ConfirmPaymentResult']['RRN'] : null;
        $this->addExtraParameters($data['ConfirmPaymentResult'] ?? []);
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
    public function getMessage($prefer = null)
    {
        return isset($this->parameters['Message']) ? $this->parameters['Message'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNumberMasked($prefer = null)
    {
        return isset($this->parameters['CardNumberMasked']) ? $this->parameters['CardNumberMasked'] : $prefer;
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
    public function getRRN($prefer = null)
    {
        return isset($this->parameters['RRN']) ? $this->parameters['RRN'] : $prefer;
    }
}
