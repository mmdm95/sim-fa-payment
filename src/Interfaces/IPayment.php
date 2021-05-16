<?php

namespace Sim\Payment\Interfaces;

use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;

interface IPayment
{
    /**
     * Create request by send a request to gateway
     *
     * @param AbstractParameterProvider $provider
     * @return void
     */
    public function createRequest(AbstractParameterProvider $provider): void;

    /**
     * Send advice to gateway to complete payment transaction
     *
     * @param AbstractAdviceParameterProvider $provider
     * @return void
     */
    public function sendAdvice(AbstractAdviceParameterProvider $provider): void;
}