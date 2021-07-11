<?php

namespace Sim\Payment\Providers\Mabna;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class MabnaHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * MabnaHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['respcode'] = isset($data['respcode']) ? $data['respcode'] : null;
        $this->parameters['respmsg'] = isset($data['respmsg']) ? $data['respmsg'] : null;
        $this->parameters['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->parameters['payload'] = isset($data['payload']) ? $data['payload'] : null;
        $this->parameters['terminalid'] = isset($data['terminalid']) ? $data['terminalid'] : null;
        $this->parameters['tracenumber'] = isset($data['tracenumber']) ? $data['tracenumber'] : null;
        $this->parameters['rrn'] = isset($data['rrn']) ? $data['rrn'] : null;
        $this->parameters['datePaid'] = isset($data['datePaid']) ? $data['datePaid'] : null;
        $this->parameters['digitalreceipt'] = isset($data['digitalreceipt']) ? $data['digitalreceipt'] : null;
        $this->parameters['issuerbank'] = isset($data['issuerbank']) ? $data['issuerbank'] : null;
        $this->parameters['payid'] = isset($data['payid']) ? $data['payid'] : null;
        $this->parameters['cardnumber'] = isset($data['cardnumber']) ? $data['cardnumber'] : null;
        $this->parameters['invoiceid'] = isset($data['invoiceid']) ? $data['invoiceid'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespCode($prefer = null)
    {
        return isset($this->parameters['respcode']) ? $this->parameters['respcode'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespMsg($prefer = null)
    {
        return isset($this->parameters['respmsg']) ? $this->parameters['respmsg'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return isset($this->parameters['amount']) ? $this->parameters['amount'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayload($prefer = null)
    {
        return isset($this->parameters['payload']) ? $this->parameters['payload'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTerminalId($prefer = null)
    {
        return isset($this->parameters['terminalid']) ? $this->parameters['terminalid'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTraceNumber($prefer = null)
    {
        return isset($this->parameters['tracenumber']) ? $this->parameters['tracenumber'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRRN($prefer = null)
    {
        return isset($this->parameters['rrn']) ? $this->parameters['rrn'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDatePaid($prefer = null)
    {
        return isset($this->parameters['datePaid']) ? $this->parameters['datePaid'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDigitalReceipt($prefer = null)
    {
        return isset($this->parameters['digitalreceipt']) ? $this->parameters['digitalreceipt'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getIssuerBank($prefer = null)
    {
        return isset($this->parameters['issuerbank']) ? $this->parameters['issuerbank'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayId($prefer = null)
    {
        return isset($this->parameters['payid']) ? $this->parameters['payid'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNumber($prefer = null)
    {
        return isset($this->parameters['cardnumber']) ? $this->parameters['cardnumber'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getInvoiceId($prefer = null)
    {
        return isset($this->parameters['invoiceid']) ? $this->parameters['invoiceid'] : $prefer;
    }
}