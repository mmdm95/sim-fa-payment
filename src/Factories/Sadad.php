<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Providers\HeaderProvider;
use Sim\Payment\Providers\Sadad\SadadAdviceResultProvider;
use Sim\Payment\Providers\Sadad\SadadRequestResultProvider;
use Sim\Payment\Providers\Sadad\SadadAdviceProvider;
use Sim\Payment\Providers\Sadad\SadadHandlerProvider;
use Sim\Payment\Utils\PaymentCurlUtil;
use Sim\Payment\Utils\SadadUtil;

class Sadad extends AbstractPayment
{
    const OPERATION_AFTER_VERIFY = 'after_verify';

    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'payment' => 'https://sadad.shaparak.ir/vpg/api/v0/Request/PaymentRequest',
        'verify' => 'https://sadad.shaparak.ir/vpg/api/v0/Advice/Verify',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'OrderId',
            'token',
            'ResCode',
        ],
        self::OPERATION_VERIFY => [
            'Token',
            'SignData',
        ],
    ];

    /**
     * @var array
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            0 => 'تراکنش موفق',
            3 => 'Invalid merchant )پذيرنده کارت فعال نیست لطفا با بخش امور پذيرندگان، تماس حاصل فرمائید(',
            23 => 'Merchant Inactive )پذيرنده کارت نامعتبر است لطفا با بخش امور پذيرندگان، تماس حاصل فرمائید(',
            58 => 'انجام تراکنش مربوطه توسط پايانه ی انجام دهنده مجاز نمی باشد',
            61 => 'مبلغ تراکنش از حد مجاز بالاتر است',
            1000 => 'ترتیب پارامترهای ارسالی اشتباه می باشد، لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند',
            1001 => 'لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند،پارامترهای پرداخت اشتباه می باشد',
            1002 => 'خطا در سیستم- تراکنش ناموفق',
            1003 => 'IP پذيرنده اشتباه است.لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند',
            1004 => 'لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند،شماره پذيرنده اشتباه است',
            1005 => 'خطای دسترسی:لطفا بعدا تلاش فرمايید',
            1006 => 'خطا در سیستم',
            1011 => 'درخواست تکراری- شماره سفارش تکراری می باشد',
            1012 => 'اطلاعات پذيرنده صحیح نیست،يکی از موارد تاريخ،زمان يا کلید تراکنش اشتباه است.لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند',
            1015 => 'پاسخ خطای نامشخص از سمت مرکز',
            1017 => 'مبلغ درخواستی شما جهت پرداخت از حد مجاز تعريف شده برای اين پذيرنده بیشتر است',
            1018 => 'اشکال در تاريخ و زمان سیستم. لطفا تاريخ و زمان سرور خود را با بانک هماهنگ نمايید',
            1019 => 'امکان پرداخت از طريق سیستم شتاب برای اين پذيرنده امکان پذير نیست',
            1020 => 'پذيرنده غیرفعال شده است.لطفا جهت فعال سازی با بانک تماس بگیريد',
            1023 => 'آدرس بازگشت پذيرنده نامعتبر است',
            1024 => 'مهر زمانی پذيرنده نامعتبر است',
            1025 => 'امضا تراکنش نامعتبر است',
            1026 => 'شماره سفارش تراکنش نامعتبر است',
            1027 => 'شماره پذيرنده نامعتبر است',
            1028 => 'شماره ترمینال پذيرنده نامعتبر است',
            1029 => 'آدرس IP پرداخت در محدوده آدرس های معتبر اعلام شده توسط پذيرنده نیست .لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند',
            1030 => 'آدرس Domain پرداخت در محدوده آدرس های معتبر اعلام شده توسط پذيرنده نیست .لطفا مسئول فنی پذيرنده با بانک تماس حاصل فرمايند',
            1031 => 'مهلت زمانی شما جهت پرداخت به پايان رسیده است.لطفا مجددا سعی بفرمايید .',
            1032 => 'پرداخت با اين کارت , برای پذيرنده مورد نظر شما امکان پذير نیست.لطفا از کارتهای مجاز که توسط پذيرنده معرفی شده است , استفاده نمايید.',
            1033 => 'به علت مشکل در سايت پذيرنده, پرداخت برای اين پذيرنده غیرفعال شده است.لطفا مسوول فنی سايت پذيرنده با بانک تماس حاصل فرمايند.',
            1036 => 'اطلاعات اضافی ارسال نشده يا دارای اشکال است',
            1037 => 'شماره پذيرنده يا شماره ترمینال پذيرنده صحیح نمیباشد',
            1053 => 'خطا: درخواست معتبر، از سمت پذيرنده صورت نگرفته است لطفا اطلاعات پذيرنده خود را چک کنید.',
            1055 => 'مقدار غیرمجاز در ورود اطلاعات',
            1056 => 'سیستم موقتا قطع میباشد.لطفا بعدا تلاش فرمايید.',
            1058 => 'سرويس پرداخت اينترنتی خارج از سرويس می باشد.لطفا بعدا سعی بفرمايید.',
            1061 => 'اشکال در تولید کد يکتا. لطفا مرورگر خود را بسته و با اجرای مجدد مرورگر « عملیات پرداخت را انجام دهید )احتمال استفاده از دکمه Back » مرورگر(',
            1064 => 'لطفا مجددا سعی بفرمايید',
            1065 => 'ارتباط ناموفق .لطفا چند لحظه ديگر مجددا سعی کنید',
            1066 => 'سیستم سرويس دهی پرداخت موقتا غیر فعال شده است',
            1068 => 'با عرض پوزش به علت بروزرسانی , سیستم موقتا قطع میباشد.',
            1072 => 'خطا در پردازش پارامترهای اختیاری پذيرنده',
            1101 => 'مبلغ تراکنش نامعتبر است',
            1103 => 'توکن ارسالی نامعتبر است',
            1104 => 'اطلاعات تسهیم صحیح نیست',
            1105 => 'تراکنش بازگشت داده شده است)مهلت زمانی به پايان رسیده است(',
        ],
        self::OPERATION_VERIFY => [
            0 => 'نتیجه تراکنش موفق است',
            -1 => 'پارامترهای ارسالی صحیح نیست و يا تراکنش در سیستم وجود ندارد.',
            101 => 'مهلت ارسال تراکنش به پايان رسیده است',
        ],
        self::OPERATION_AFTER_VERIFY => [
            0 => 'نتیجه تراکنش موفق است',
            -1 => 'نتیجه تراکنش ناموفق است.',
        ],
    ];

    /**
     * Sadad constructor.
     * @param string $key
     * @param string $merchantId
     * @param string $terminalId
     */
    public function __construct(string $key, string $merchantId, string $terminalId)
    {
        parent::__construct();

        // Set the key
        $this->parameters['key'] = $key;
        // Set merchant id key
        $this->parameters['MerchantId'] = $merchantId;
        // Set terminal id key
        $this->parameters['TerminalId'] = $terminalId;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);

        $provider->setExtraParameter('MerchantId', $this->parameters['MerchantId'])
            ->setExtraParameter('TerminalId', $this->parameters['TerminalId'])
            ->setExtraParameter(
                'SignData',
                SadadUtil::encryptPkcs7(
                    "{$this->parameters['TerminalId']};{$provider->getParameter('OrderId')};{$provider->getParameter('Amount')}",
                    $this->parameters['key']
                )
            );
        $result = $this->request($provider->getParameters(), $this->urls['payment']);
        $resProvider = new SadadRequestResultProvider($result['response']);
        // res code is null on success(this is not documented! shame on them)
        if ($resProvider->getResCode() == 0) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getResCode(),
                $resProvider->getDescription(),
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

        $resProvider = new SadadHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (
            !is_null($resProvider->getOrderId()) &&
            !is_null($resProvider->getToken()) &&
            !is_null($resProvider->getResCode()) &&
            $resProvider->getResCode(-1) == 0
        ) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new SadadAdviceProvider();
            $provider->setExtraParameter('Token', $resProvider->getToken())
                ->setExtraParameter('SignData', SadadUtil::encryptPkcs7($resProvider->getToken(), $this->parameters['key']));

            $result = $this->request($provider->getParameters(), $this->urls['verify']);

            $adviceProvider = new SadadAdviceResultProvider($result['response']);
            // res code is null on success(this is not documented! shame on them)
            if ($adviceProvider->getResCode() == 0) {
                $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider, $resProvider]);
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $adviceProvider->getResCode(),
                    'تراکنش نا موفق بود در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برمی گردد.',
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

        $newData = json_encode($data);

        $curlProvider = new CurlProvider();
        $curlProvider->setUrl($url);
        $curlProvider->setRequestMethod(PaymentFactory::METHOD_POST);
        $curlProvider->setFields($newData);
        $curlProvider->setReturnTransfer(true);

        //----- Add some header
        $headerProvider = new HeaderProvider();
        $headerProvider->contentType('application/json');
        $headerProvider->contentLength(strlen($newData));
        //-----

        $curlProvider->setHeader($headerProvider);

        // Send request to gateway
        $response = PaymentCurlUtil::request($curlProvider);

        // reset timezone to original
        date_default_timezone_set($prevTimezone);

        return $response;
    }
}
