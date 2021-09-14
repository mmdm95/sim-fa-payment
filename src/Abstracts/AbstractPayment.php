<?php

namespace Sim\Payment\Abstracts;

use Sim\Payment\Interfaces\IPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Utils\PaymentCurlUtil;

abstract class AbstractPayment extends AbstractPaymentEvent implements IPayment
{
    // operation constants
    const OPERATION_REQUEST = 'request';
    const OPERATION_VERIFY = 'verify';

    /**
     * For those gateways that need to connect with Soap
     * @var \SoapClient|null $client
     */
    protected $client = null;

    /**
     * @var string
     */
    protected $handlerMethod = PaymentFactory::METHOD_POST;

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
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [],
        self::OPERATION_VERIFY => [],
    ];

    /**
     * @var string $unknown_message
     */
    protected $unknown_message = 'پیام نامشخص';

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
    abstract protected function request(array $data, string $url);

    /**
     * @param array $data
     * @return array
     */
    protected function handleRequest(array $data): array
    {
        $result = [];
        if (strtoupper($_SERVER["REQUEST_METHOD"]) == $this->handlerMethod) {
            $method = $this->handlerMethod == PaymentFactory::METHOD_GET ? $_GET : $_POST;
            foreach ($data as $name) {
                $result[$name] = isset($method[$name])
                    ? PaymentCurlUtil::escapeData($method[$name])
                    : null;
            }
        }
        return $result;
    }
}
