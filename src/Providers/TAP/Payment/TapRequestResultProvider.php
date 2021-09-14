<?php

namespace Sim\Payment\Providers\TAP\Payment;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class TapRequestResultProvider extends AbstractBaseParameterProvider
{
    /**
     * TapRequestResultProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Status'] = isset($data['SalePaymentRequestResult']['Status']) ? $data['SalePaymentRequestResult']['Status'] : null;
        $this->parameters['Token'] = isset($data['SalePaymentRequestResult']['Token']) ? $data['SalePaymentRequestResult']['Token'] : null;
        $this->parameters['Message'] = isset($data['SalePaymentRequestResult']['Message']) ? $data['SalePaymentRequestResult']['Message'] : null;
        $this->addExtraParameters($data['SalePaymentRequestResult'] ?? []);
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
    public function getToken($prefer = null)
    {
        return isset($this->parameters['Token']) ? $this->parameters['Token'] : $prefer;
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
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://pec.shaparak.ir/NewIPG/?Token=' . (string)$this->getToken();
    }
}
