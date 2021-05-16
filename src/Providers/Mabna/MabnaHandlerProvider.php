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
        $this->parameters['respcode'] = $data['respcode'];
        $this->parameters['respmsg'] = $data['respmsg'];
        $this->parameters['amount'] = $data['amount'];
        $this->parameters['payload'] = $data['payload'];
        $this->parameters['terminalid'] = $data['terminalid'];
        $this->parameters['tracenumber'] = $data['tracenumber'];
        $this->parameters['rrn'] = $data['rrn'];
        $this->parameters['datePaid'] = $data['datePaid'];
        $this->parameters['digitalreceipt'] = $data['digitalreceipt'];
        $this->parameters['issuerbank'] = $data['issuerbank'];
        $this->parameters['payid'] = $data['payid'];
        $this->parameters['cardnumber'] = $data['cardnumber'];
        $this->parameters['invoiceid'] = $data['invoiceid'];
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespCode($prefer = null)
    {
        return $this->parameters['respcode'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespMsg($prefer = null)
    {
        return $this->parameters['respmsg'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return $this->parameters['amount'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayload($prefer = null)
    {
        return $this->parameters['payload'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTerminalId($prefer = null)
    {
        return $this->parameters['terminalid'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTraceNumber($prefer = null)
    {
        return $this->parameters['tracenumber'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRRN($prefer = null)
    {
        return $this->parameters['rrn'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDatePaid($prefer = null)
    {
        return $this->parameters['datePaid'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDigitalReceipt($prefer = null)
    {
        return $this->parameters['digitalreceipt'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getIssuerBank($prefer = null)
    {
        return $this->parameters['issuerbank'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayId($prefer = null)
    {
        return $this->parameters['payid'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNumber($prefer = null)
    {
        return $this->parameters['cardnumber'] ?: $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getInvoiceId($prefer = null)
    {
        return $this->parameters['invoiceid'] ?: $prefer;
    }
}