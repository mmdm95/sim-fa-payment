<?php

namespace Sim\Payment\Providers\Mabna;

use Sim\Payment\Abstracts\AbstractParameterProvider;

class MabnaRequestProvider extends AbstractParameterProvider
{
    /**
     * @param $invoice
     * @return static
     */
    public function setInvoiceId($invoice)
    {
        $this->parameters['invoiceID'] = $invoice;
        return $this;
    }

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
     * @param $url
     * @return static
     */
    public function setCallbackUrl($url)
    {
        $this->parameters['callbackURL'] = $url;
        return $this;
    }
}