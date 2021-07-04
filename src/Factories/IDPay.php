<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Providers\HeaderProvider;
use Sim\Payment\Providers\IDPay\IDPayAdviceResultProvider;
use Sim\Payment\Providers\IDPay\IDPayRequestResultProvider;
use Sim\Payment\Providers\IDPay\IDPayAdviceProvider;
use Sim\Payment\Providers\IDPay\IDPayHandlerProvider;
use Sim\Payment\Utils\PaymentCurlUtil;

class IDPay extends AbstractPayment
{
    // mode constants
    const MODE_DEVELOPMENT = 1;
    const MODE_PRODUCTION = 2;

    /**
     * {@inheritdoc}
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            11 => 'کاربر مسدود شده است.',
            12 => 'API Key یافت نشد.',
            13 => 'درخواست شما از {ip} ارسال شده است. این IP با IP های ثبت شده در وب سرویس همخوانی ندارد.',
            14 => 'وب سرویس تایید نشده است.',
            21 => 'حساب بانکی متصل به وب سرویس تایید نشده است.',
            31 => 'کد تراکنش id نباید خالی باشد.',
            32 => 'شماره سفارش order_id نباید خالی باشد.',
            33 => 'مبلغ نباید خالی باشد.',
            34 => 'مبلغ باید بیشتر از ۱۰،۰۰۰ ریال باشد.',
            35 => 'مبلغ باید کمتر از ۵۰۰،۰۰۰،۰۰۰ ریال باشد.',
            36 => 'مبلغ بیشتر از حد مجاز است.',
            37 => 'آدرس بازگشت callback نباید خالی باشد.',
            38 => 'درخواست شما از آدرس {domain} ارسال شده است. دامنه آدرس بازگشت callback با آدرس ثبت شده در وب سرویس همخوانی ندارد.',
            51 => 'تراکنش ایجاد نشد.',
            52 => 'استعلام نتیجه ای نداشت.',
            53 => 'تایید پرداخت امکان پذیر نیست.',
            54 => 'مدت زمان تایید پرداخت سپری شده است.',
        ],
        self::OPERATION_VERIFY => [
            -2 => 'هیچ وضعیتی تنظیم نشده‌است',
            1 => 'پرداخت انجام نشده است',
            2 => 'پرداخت ناموفق بوده است',
            3 => 'خطا رخ داده است',
            4 => 'بلوکه شده',
            5 => 'برگشت به پرداخت کننده',
            6 => 'برگشت خورده سیستمی',
            7 => 'انصراف از پرداخت',
            8 => 'به درگاه پرداخت منتقل شد',
            10 => 'در انتظار تایید پرداخت',
            100 => 'پرداخت موفق',
            101 => 'پرداخت قبلا انجام شده',
            200 => 'به دریافت کننده واریز شد',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'payment' => 'https://api.idpay.ir/v1.1/payment',
        'inquiry' => 'https://api.idpay.ir/v1.1/payment/inquiry',
        'verify' => 'https://api.idpay.ir/v1.1/payment/verify',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'status',
            'track_id',
            'id',
            'order_id',
            'amount',
            'card_no',
            'hashed_card_no',
            'date',
        ],
    ];

    /**
     * IDPay constructor.
     * @param string|null $apiKey
     * @param int $mode
     */
    public function __construct(string $apiKey, int $mode = self::MODE_PRODUCTION)
    {
        parent::__construct();

        // Set api key
        $this->parameters['APIKey'] = $apiKey;
        // Set mode
        $this->parameters['mode'] = $mode;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);
        $result = $this->request($provider->getParameters(), $this->urls['payment']);
        $resProvider = new IDPayRequestResultProvider($result['response']);

        if (empty($resProvider->getErrorCode()) && !empty($resProvider->getId()) && !empty($resProvider->getLink())
        ) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getErrorCode(),
                $this->getMessage($resProvider->getErrorCode(), self::OPERATION_REQUEST),
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

        $resProvider = new IDPayHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (!empty($resProvider->getOrderId()) && !empty($resProvider->getStatus())) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new IDPayAdviceProvider();
            $provider->setExtraParameter('id', $resProvider->getId())
                ->setExtraParameter('order_id', $resProvider->getOrderId());

            $result = $this->request($provider->getParameters(), $this->urls['verify']);

            $adviceProvider = new IDPayAdviceResultProvider($result['response']);
            if (empty($adviceProvider->getErrorCode())) {
                $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider, $resProvider]);
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $adviceProvider->getErrorCode(),
                    $this->getMessage($adviceProvider->getErrorCode(), self::OPERATION_VERIFY),
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

        //----- Add some header
        $headerProvider = new HeaderProvider();
        $headerProvider->contentType('application/json');
        $headerProvider->addHeader('X-API-KEY', $this->parameters['APIKey']);

        if (!empty($this->parameters['mode']) &&
            $this->parameters['mode'] == self::MODE_DEVELOPMENT) {
            $headerProvider->addHeader('X-SANDBOX', 1);
        }
        //-----

        $curlProvider->setHeader($headerProvider);

        // Send request to gateway
        $response = PaymentCurlUtil::request($curlProvider);

        // reset timezone to original
        date_default_timezone_set($prevTimezone);

        return $response;
    }
}
