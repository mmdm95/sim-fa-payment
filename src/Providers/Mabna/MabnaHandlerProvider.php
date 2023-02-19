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
        $this->parameters['respcode'] = $data['respcode'] ?? null;
        $this->parameters['respmsg'] = $data['respmsg'] ?? null;
        $this->parameters['amount'] = $data['amount'] ?? null;
        $this->parameters['payload'] = $data['payload'] ?? null;
        $this->parameters['terminalid'] = $data['terminalid'] ?? null;
        $this->parameters['tracenumber'] = $data['tracenumber'] ?? null;
        $this->parameters['rrn'] = $data['rrn'] ?? null;
        $this->parameters['datePaid'] = $data['datePaid'] ?? null;
        $this->parameters['digitalreceipt'] = $data['digitalreceipt'] ?? null;
        $this->parameters['issuerbank'] = $data['issuerbank'] ?? null;
        $this->parameters['payid'] = $data['payid'] ?? null;
        $this->parameters['cardnumber'] = $data['cardnumber'] ?? null;
        $this->parameters['invoiceid'] = $data['invoiceid'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespCode($prefer = null)
    {
        return $this->parameters['respcode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRespMsg($prefer = null)
    {
        return $this->parameters['respmsg'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return $this->parameters['amount'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayload($prefer = null)
    {
        return $this->parameters['payload'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTerminalId($prefer = null)
    {
        return $this->parameters['terminalid'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTraceNumber($prefer = null)
    {
        return $this->parameters['tracenumber'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRRN($prefer = null)
    {
        return $this->parameters['rrn'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDatePaid($prefer = null)
    {
        return $this->parameters['datePaid'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDigitalReceipt($prefer = null)
    {
        return $this->parameters['digitalreceipt'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getIssuerBank($prefer = null)
    {
        return $this->parameters['issuerbank'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getPayId($prefer = null)
    {
        return $this->parameters['payid'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNumber($prefer = null)
    {
        return $this->parameters['cardnumber'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getInvoiceId($prefer = null)
    {
        return $this->parameters['invoiceid'] ?? $prefer;
    }
}
