<?php

namespace Sim\Payment\Providers\IranKish;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class IranKishRequestProvider extends AbstractParameterProvider
{
    /**
     * IranKishParameterProvider constructor.
     */
    public function __construct()
    {
        $this->setBillInfo(null);
        $this->setPaymentId(null);
        $this->setRequestTimestamp(time());
        $this->setTransactionType('Purchase');
    }

    /**
     * @param $amount
     * @return static
     */
    public function setAmount($amount): self
    {
        $this->parameters['amount'] = $amount;
        return $this;
    }

    /**
     * @param $info
     * @return static
     */
    public function setBillInfo($info): self
    {
        $this->parameters['billInfo'] = $info;
        return $this;
    }

    /**
     * @param $paymentId
     * @return static
     */
    public function setPaymentId($paymentId): self
    {
        $this->parameters['paymentId'] = $paymentId;
        return $this;
    }

    /**
     * @param $requestId
     * @return static
     */
    public function setRequestId($requestId): self
    {
        $this->parameters['requestId'] = $requestId;
        return $this;
    }

    /**
     * @param $requestTimestamp
     * @return static
     */
    public function setRequestTimestamp($requestTimestamp): self
    {
        $this->parameters['requestTimestamp'] = $requestTimestamp;
        return $this;
    }

    /**
     * @param $revertUrl
     * @return static
     */
    public function setRevertUrl($revertUrl): self
    {
        $this->parameters['revertUri'] = $revertUrl;
        return $this;
    }

    /**
     * @param $transactionType
     * @return static
     */
    public function setTransactionType($transactionType): self
    {
        $this->parameters['transactionType'] = $transactionType;
        return $this;
    }
}
