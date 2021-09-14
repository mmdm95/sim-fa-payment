<?php

namespace Sim\Payment\Providers\TAP\Payment;

use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Utils\ConverterUtil;

class TapRequestProvider extends AbstractParameterProvider
{
    /**
     * @param $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->parameters['Amount'] = $amount;
        return $this;
    }

    /**
     * @param $returnUrl
     * @return static
     */
    public function setCallBackUrl($returnUrl)
    {
        $this->parameters['CallBackUrl'] = $returnUrl;
        return $this;
    }

    /**
     * @param $orderId
     * @return static
     */
    public function setOrderId($orderId)
    {
        $this->parameters['OrderId'] = $orderId;
        return $this;
    }

    /**
     * @param string $additionalData
     * @return static
     */
    public function setAdditionalData(string $additionalData)
    {
        $this->parameters['AdditionalData'] = $additionalData;
        return $this;
    }

    /**
     * @param string $originator
     * @return static
     */
    public function setOriginator(string $originator)
    {
        $originator = $this->normalizeOriginator($originator);
        if (!empty($originator)) {
            $this->parameters['Originator'] = $originator;
        }
        return $this;
    }

    /**
     * @param $originator
     * @return string
     */
    private function normalizeOriginator($originator): string
    {
        $newOriginator = '';

        if (\preg_match('/^(0)?9\d{9}$/', $originator)) {
            $newOriginator = substr(ConverterUtil::toEnglish($originator), 1);
        } elseif (\preg_match('/^(098|\+98)?9\d{9}$/', $originator)) {
            $newOriginator = substr(ConverterUtil::toEnglish($originator), 3);
        }

        return $newOriginator;
    }
}
