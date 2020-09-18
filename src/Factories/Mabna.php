<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Utils\Curl;

class Mabna extends AbstractPayment
{
    // operation constants
    const OPERATION_REQUEST = 'request';
    const OPERATION_VERIFY = 'verify';

    /**
     * {@inheritdoc}
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            -2 => 'Not Set',
            -1 => 'NOK',
            100 => 'OK',
            101 => 'Duplicate',
        ],
        self::OPERATION_VERIFY => [
            -1 => 'تراکنش پیدا نشد.',
            -2 => 'تراکنش قبلا Reverse شده است.',
            -3 => 'Total Error خطای عمومی – خطای Exception ها',
            -4 => 'امکان انجام درخواست برای این تراکنش وجود ندارد.',
            -5 => 'آدرس IP نامعتبر میباشد ) IP در لیست آدرسهای معرفی شده توسط پذیرنده موجود نمیباشد(',
            -6 => 'عدم فعال بودن سرویس برگشت تراکنش برای پذیرنده',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'get_token' => 'https://mabna.shaparak.ir:8081/V1/PeymentApi/GetToken',
        'payment' => 'https://mabna.shaparak.ir:8080/Pay',
        'bill' => 'https://mabna.shaparak.ir:8080/Bill',
        'batch_bill' => 'https://mabna.shaparak.ir:8080/BatchBill',
        'charge' => 'https://mabna.shaparak.ir:8080/Charge',
        'mobile_payment' => 'https://mabna.shaparak.ir:8080/Mpay',
        'mobile_bill' => 'https://mabna.shaparak.ir:8080/MBill',
        'mobile_batch_bill' => 'https://mabna.shaparak.ir:8080/MBatchBill',
        'mobile_charge' => 'https://mabna.shaparak.ir:8080/MCharge',
        'verify' => 'https://mabna.shaparak.ir:8081/V1/PeymentApi/Advice',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'respcode',
            'respmsg',
            'amount',
            'invoiceid',
            'payload',
            'terminalid',
            'tracenumber',
            'rrn',
            'datePaid',
            'digitalreceipt',
            'issuerbank',
            'billid',
            'payid',
            'cardnumber',
            'pincharge',
            'refcharge',
            'serialcharge',
        ],
        self::OPERATION_VERIFY => [
            'digitalreceipt',
            'Tid',
        ],
    ];

    /**
     * Mabna constructor.
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest()
    {
        $result = [];
        if ($_SERVER["REQUEST_METHOD"] == PaymentFactory::METHOD_POST) {
            foreach ($this->gateway_variables_name[self::OPERATION_REQUEST] as $name) {
                ${$name} = isset($_POST[$name]) ? Curl::escapeData($_POST[$name]) : null;
                $result[$name] = ${$name};
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest()
    {
        return $this->request($this->getParameters(), $this->urls['payment']);
    }

    /**
     * @return array|mixed
     */
    public function getToken()
    {
        return $this->request($this->getParameters(), $this->urls['get_token']);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAdvice()
    {
        $sendData = array_intersect_key($this->getParameters(), array_flip($this->gateway_variables_name[self::OPERATION_VERIFY]));
        return $this->request($sendData, $this->urls['verify']);
    }

    /**
     * {@inheritdoc}
     */
    protected function request(array $data, string $url)
    {
        $prevTimezone = date_default_timezone_get();

        // set timezone to tehran - because it is a persian library
        date_default_timezone_set("Asia/Tehran");

        $curlProvider = new CurlProvider();
        $curlProvider->setUrl($url);
        $curlProvider->setRequestMethod(PaymentFactory::METHOD_POST);
        $curlProvider->setFields($data);
        $curlProvider->setReturnTransfer(true);
        $curlProvider->setSSLVerifyHost(false);

        // Send request to gateway
        $response = Curl::request($curlProvider);

        // reset timezone to original
        date_default_timezone_set($prevTimezone);

        return $response;
    }
}