<?php

namespace Sim\Payment\Providers\IranKish;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IranKishAdviceResultProvider extends AbstractBaseParameterProvider
{
    /**
     * IranKishResultProvider constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->parameters['responseCode'] = $data['responseCode'] ?? null;
        $this->parameters['description'] = $data['description'] ?? null;
        $this->parameters['status'] = $data['status'] ?? null;
        $this->parameters['result'] = $data['result'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResponseCode($prefer = null)
    {
        return $this->parameters['responseCode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDescription($prefer = null)
    {
        return $this->parameters['description'] ?? $prefer;
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
    public function getResult($prefer = null)
    {
        return $this->parameters['result'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultResponseCode($prefer = null)
    {
        return $this->parameters['result']['responseCode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultSystemTraceAuditNumber($prefer = null)
    {
        return $this->parameters['result']['systemTraceAuditNumber'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultRetrievalReferenceNumber($prefer = null)
    {
        return $this->parameters['result']['retrievalReferenceNumber'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultTransactionDate($prefer = null)
    {
        return $this->parameters['result']['transactionDate'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultTransactionTime($prefer = null)
    {
        return $this->parameters['result']['transactionTime'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultProcessCode($prefer = null)
    {
        return $this->parameters['result']['processCode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultBillType($prefer = null)
    {
        return $this->parameters['result']['billType'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultBillId($prefer = null)
    {
        return $this->parameters['result']['billId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultPaymentId($prefer = null)
    {
        return $this->parameters['result']['paymentId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResultAmount($prefer = null)
    {
        return $this->parameters['result']['amount'] ?? $prefer;
    }
}
