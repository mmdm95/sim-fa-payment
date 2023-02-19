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
        $this->parameters['Status'] = $data['SalePaymentRequestResult']['Status'] ?? null;
        $this->parameters['Token'] = $data['SalePaymentRequestResult']['Token'] ?? null;
        $this->parameters['Message'] = $data['SalePaymentRequestResult']['Message'] ?? null;
        $this->addExtraParameters($data['SalePaymentRequestResult'] ?? []);
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
    public function getToken($prefer = null)
    {
        return $this->parameters['Token'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getMessage($prefer = null)
    {
        return $this->parameters['Message'] ?? $prefer;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://pec.shaparak.ir/NewIPG/?Token=' . (string)$this->getToken();
    }
}
