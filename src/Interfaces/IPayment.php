<?php

namespace Sim\Payment\Interfaces;

interface IPayment
{
    /**
     * @param string $parameter_name
     * @param $parameter_value
     * @return static
     */
    public function setParameter(string $parameter_name, $parameter_value);

    /**
     * @param string $parameter_name
     * @param mixed|null $prefer
     * @return mixed
     */
    public function getParameter(string $parameter_name, $prefer = null);

    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @return static
     */
    public function resetParameters();

    /**
     * Handle requested operation that comes from gateway
     *
     * @return mixed
     */
    public function handleRequest();

    /**
     * Create request by send a request to gateway
     *
     * @return mixed
     */
    public function createRequest();

    /**
     * Send advice to gateway to complete payment transaction
     *
     * @return mixed
     */
    public function sendAdvice();
}