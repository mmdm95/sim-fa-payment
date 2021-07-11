<?php

namespace Sim\Payment\Factories;

use Sim\Event\Event;
use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Providers\Mabna\MabnaAdviceProvider;
use Sim\Payment\Providers\Mabna\MabnaAdviceResultProvider;
use Sim\Payment\Providers\Mabna\MabnaHandlerProvider;
use Sim\Payment\Providers\Mabna\MabnaRequestResultProvider;
use Sim\Payment\Utils\PaymentCurlUtil;

class Mabna extends AbstractPayment
{
    // event constants
    const DUPLICATE_SEND_ADVICE = 'send-advice:duplicate';

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
     * @param string $terminalId
     */
    public function __construct(string $terminalId)
    {
        parent::__construct();

        // extra events
        $this->event_provider->addEvent(new Event(self::DUPLICATE_SEND_ADVICE));

        // Set terminal id
        $this->parameters['terminalID'] = $terminalId;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);
        $provider->setExtraParameter('terminalID', $this->parameters['terminalID']);
        $result = $this->request($provider->getParameters(), $this->urls['get_token']);
        $resProvider = new MabnaRequestResultProvider(array_merge($result['response'], ['Url' => $this->urls['payment']]));

        if (!is_null($resProvider->getStatus()) && !is_null($resProvider->getAccessToken()) && $resProvider->getStatus() == 0) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getStatus(),
                $this->getMessage($resProvider->getStatus(), self::OPERATION_VERIFY),
                $resProvider
            ]);
        }

        $this->emitter->dispatch(self::AF_CREATE_REQUEST, [$resProvider]);
    }

    /**
     * You DO NOT NEED to send provider to this method
     *
     * {@inheritdoc}
     */
    public function sendAdvice(AbstractAdviceParameterProvider $provider = null): void
    {
        $this->emitter->dispatch(self::BF_HANDLE_RESULT);

        $resProvider = new MabnaHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (
            !is_null($resProvider->getRespCode()) && !is_null($resProvider->getRespMsg()) && !is_null($resProvider->getAmount()) &&
            !is_null($resProvider->getPayload()) && !is_null($resProvider->getTerminalId()) && !is_null($resProvider->getTraceNumber()) &&
            !is_null($resProvider->getRRN()) && !is_null($resProvider->getDatePaid()) && !is_null($resProvider->getDigitalReceipt()) &&
            !is_null($resProvider->getIssuerBank()) && !is_null($resProvider->getPayId()) &&
            !is_null($resProvider->getCardNumber()) && !is_null($resProvider->getInvoiceId()) &&
            $resProvider->getRespCode() == 0 && $resProvider->getTerminalId() == $this->parameters['terminalID']
        ) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new MabnaAdviceProvider();
            $provider->setExtraParameter('Tid', $this->parameters['terminalID'])
                ->setExtraParameter('digitalreceipt', $resProvider->getDigitalReceipt());

            $result = $this->request($provider->getParameters(), $this->urls['verify']);

            $adviceProvider = new MabnaAdviceResultProvider($result['response']);
            if (!is_null($adviceProvider->getStatus()) &&
                ($adviceProvider->getStatus() == 'OK' || $adviceProvider->getStatus() == 'Duplicate')
            ) {
                if ($adviceProvider->getStatus() == 'OK') {
                    $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider, $resProvider]);
                } else {
                    $this->emitter->dispatch(self::DUPLICATE_SEND_ADVICE, [$adviceProvider, $resProvider]);
                }
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $adviceProvider->getReturnId(),
                    $this->getMessage($adviceProvider->getReturnId(), self::OPERATION_VERIFY),
                    $adviceProvider,
                    $resProvider
                ]);
            }
            $this->emitter->dispatch(self::AF_SEND_ADVICE, [$adviceProvider, $resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_HANDLE_RESULT, [$resProvider]);
        }
        $this->emitter->dispatch(self::AF_HANDLE_RESULT, [$resProvider]);
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
        $response = PaymentCurlUtil::request($curlProvider);

        // reset timezone to original
        date_default_timezone_set($prevTimezone);

        return $response;
    }
}
