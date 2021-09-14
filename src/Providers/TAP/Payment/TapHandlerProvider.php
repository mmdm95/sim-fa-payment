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
        $this->parameters['Token'] = isset($data['Token']) ? $data['Token'] : null;
        $this->parameters['status'] = isset($data['status']) ? $data['status'] : null;
        $this->parameters['OrderId'] = isset($data['OrderId']) ? $data['OrderId'] : null;
        $this->parameters['TerminalNo'] = isset($data['TerminalNo']) ? $data['TerminalNo'] : null;
        $this->parameters['Amount'] = isset($data['Amount']) ? $data['Amount'] : null;
        $this->parameters['RRN'] = isset($data['RRN']) ? $data['RRN'] : null;
        $this->addExtraParameters($data);
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
    public function getStatus($prefer = null)
    {
        return isset($this->parameters['status']) ? $this->parameters['status'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return isset($this->parameters['OrderId']) ? $this->parameters['OrderId'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTerminalNo($prefer = null)
    {
        return isset($this->parameters['TerminalNo']) ? $this->parameters['TerminalNo'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return isset($this->parameters['Amount']) ? $this->parameters['Amount'] : $prefer;
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
