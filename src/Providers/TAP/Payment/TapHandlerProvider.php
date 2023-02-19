<?php

namespace Sim\Payment\Providers\TAP\Payment;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class TapHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * TapHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['Token'] = $data['Token'] ?? null;
        $this->parameters['status'] = $data['status'] ?? null;
        $this->parameters['OrderId'] = $data['OrderId'] ?? null;
        $this->parameters['TerminalNo'] = $data['TerminalNo'] ?? null;
        $this->parameters['Amount'] = $data['Amount'] ?? null;
        $this->parameters['RRN'] = $data['RRN'] ?? null;
        $this->addExtraParameters($data);
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
    public function getStatus($prefer = null)
    {
        return $this->parameters['status'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return $this->parameters['OrderId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTerminalNo($prefer = null)
    {
        return $this->parameters['TerminalNo'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return $this->parameters['Amount'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRRN($prefer = null)
    {
        return $this->parameters['RRN'] ?? $prefer;
    }
}
