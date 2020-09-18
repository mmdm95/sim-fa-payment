<?php

namespace Sim\Payment\Abstracts;

use Sim\Payment\Interfaces\IPayment;

abstract class AbstractPayment implements IPayment
{
    /**
     * For those gateways that need to connect with Soap
     * @var \SoapClient|null $client
     */
    protected $client = null;

    /**
     * @var array $parameters
     */
    protected $parameters = [];

    /**
     * @var array $sms_code_message
     */
    protected $code_message = [];

    /**
     * @var array $urls
     */
    protected $urls = [];

    /**
     * Variables that return from gateway after request/advice
     * [
     *   'request' => [
     *     request parameters
     *   ],
     *   'advice' => [
     *     advice parameters
     *   ],
     *   ...
     * ]
     *
     * @var array $gateway_variables_name
     */
    protected $gateway_variables_name = [];

    /**
     * @var string $unknown_message
     */
    protected $unknown_message = 'پیام نامشخص';

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setParameter($name, $value);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->getParameter($name);
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(string $parameter_name, $parameter_value)
    {
        $this->parameters[$parameter_name] = $parameter_value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter(string $parameter_name, $prefer = null)
    {
        return $this->parameters[$parameter_name] ?? $prefer;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return static
     */
    public function resetParameters()
    {
        $this->parameters = [];
        return $this;
    }

    /**
     * @param int $code
     * @param string $operation
     * @return string
     */
    public function getMessage(int $code, string $operation): string
    {
        return $this->code_message[$operation][$code] ?? $this->unknown_message;
    }

    /**
     * @param array $data
     * @param string $url
     * @return mixed
     */
    protected function request(array $data, string $url)
    {
        // Do whatever needed for gateway or do nothing
        return null;
    }
}