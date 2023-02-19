<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Providers\HeaderProvider;
use Sim\Payment\Providers\IranKish\IranKishAdviceProvider;
use Sim\Payment\Providers\IranKish\IranKishAdviceResultProvider;
use Sim\Payment\Providers\IranKish\IranKishHandlerProvider;
use Sim\Payment\Providers\IranKish\IranKishRequestResultProvider;
use Sim\Payment\Utils\IranKishUtil;
use Sim\Payment\Utils\PaymentCurlUtil;

class IranKish extends AbstractPayment
{
    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'payment' => 'https://ikc.shaparak.ir/api/v3/tokenization/make',
        'verify' => 'https://ikc.shaparak.ir/api/v3/confirmation/purchase',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'token',
            'responseCode',
            'retrievalReferenceNumber',
            'systemTraceAuditNumber',
        ],
        self::OPERATION_VERIFY => [
            'token',
            'acceptorId',
            'responseCode',
            'paymentId',
            'RequestId',
            'sha256OfPan',
            'retrievalReferenceNumber',
            'amount',
            'maskedPan',
            'systemTraceAuditNumber',
        ],
    ];

    /**
     * @var array
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            // there are more codes, but it's just limited codes below
            // for more information see https://en.wikipedia.org/wiki/ISO_8583#Response_code
            5 => 'از انجام تراکنش صرف نظر شد',
            3 => 'پذیرنده فروشگاهی نا معتبر است',
            64 => 'مبلغ تراکنش نادرست است،جمع مبالغ تقسیم وجوه برابر مبلغ کل تراکنش نمی باشد',
            94 => 'تراکنش تکراری است',
            25 => 'تراکنش اصلی یافت نشد',
            77 => 'روز مالی تراکنش نا معتبر است',
            63 => 'کد اعتبار سنجی پیام نا معتبر است',
            97 => 'کد تولید کد اعتبار سنجی نا معتبر است',
            30 => 'فرمت پیام نادرست است',
            86 => 'شتاب در حال Off Sign است',
            55 => 'رمز کارت نادرست است',
            40 => 'عمل درخواستی پشتیبانی نمی شود',
            57 => 'انجام تراکنش مورد درخواست توسط پایانه انجام دهنده مجاز نمی باشد',
            58 => 'انجام تراکنش مورد درخواست توسط پایانه انجام دهنده مجاز نمی باشد',
            96 => 'قوانین سامانه نقض گردیده است ، خطای داخلی سامانه',
            2 => 'تراکنش قبال برگشت شده است',
            54 => 'تاریخ انقضا کارت سررسید شده است',
            62 => 'کارت محدود شده است',
            75 => 'تعداد دفعات ورود رمز اشتباه از حد مجاز فراتر رفته است',
            14 => 'اطالعات کارت صحیح نمی باشد',
            51 => 'موجودی حساب کافی نمی باشد',
            56 => 'اطالعات کارت یافت نشد',
            61 => 'مبلغ تراکنش بیش از حد مجاز است',
            65 => 'تعداد دفعات انجام تراکنش بیش از حد مجاز است',
            78 => 'کارت فعال نیست',
            79 => 'حساب متصل به کارت بسته یا دارای اشکال است',
            42 => 'کارت یا حساب مبدا/مقصد در وضعیت پذیرش نمی باشد',
            31 => 'عدم تطابق کد ملی خریدار با دارنده کارت',
            98 => 'سقف استفاده از رمز دوم ایستا به پایان رسیده است',
            901 => 'درخواست نا معتبر است )Tokenization)',
            902 => 'پارامترهای اضافی درخواست نامعتبر می باشد )Tokenization)',
            903 => 'شناسه پرداخت نامعتبر می باشد )Tokenization)',
            904 => 'اطالعات مرتبط با قبض نا معتبر می باشد )Tokenization)',
            905 => 'شناسه درخواست نامعتبر می باشد )Tokenization)',
            906 => 'درخواست تاریخ گذشته است )Tokenization)',
            907 => 'آدرس بازگشت نتیجه پرداخت نامعتبر می باشد )Tokenization)',
            909 => 'پذیرنده نامعتبر می باشد)Tokenization)',
            910 => 'پارامترهای مورد انتظار پرداخت تسهیمی تامین نگردیده است)Tokenization)',
            911 => 'پارامترهای مورد انتظار پرداخت تسهیمی نا معتبر یا دارای اشکال می باشد)Tokenization)',
            912 => 'تراکنش درخواستی برای پذیرنده فعال نیست )Tokenization)',
            913 => 'تراکنش تسهیم برای پذیرنده فعال نیست )Tokenization)',
            914 => 'آدرس آی پی دریافتی درخواست نا معتبر می باشد',
            915 => 'شماره پایانه نامعتبر می باشد )Tokenization)',
            916 => 'شماره پذیرنده نا معتبر می باشد )Tokenization)',
            917 => 'نوع تراکنش اعالم شده در خواست نا معتبر می باشد )Tokenization)',
            918 => 'پذیرنده فعال نیست)Tokenization)',
            919 => 'مبالغ تسهیمی ارائه شده با توجه به قوانین حاکم بر وضعیت تسهیم پذیرنده ، نا معتبر است )Tokenization)',
            920 => 'شناسه نشانه نامعتبر می باشد',
            921 => 'شناسه نشانه نامعتبر و یا منقضی شده است',
            922 => 'نقض امنیت درخواست )Tokenization)',
            923 => 'ارسال شناسه پرداخت در تراکنش قبض مجاز نیست)Tokenization)',
            928 => 'مبلغ مبادله شده نا معتبر می باشد)Tokenization)',
            929 => 'شناسه پرداخت ارائه شده با توجه به الگوریتم متناظر نا معتبر می باشد)Tokenization)',
            930 => 'کد ملی ارائه شده نا معتبر می باشد)Tokenization)',
        ],
    ];

    /**
     * IranKish constructor.
     * @param string $terminalId
     * @param string $password
     * @param string $acceptorId
     * @param string $publicKey
     */
    public function __construct(string $terminalId, string $password, string $acceptorId, string $publicKey)
    {
        parent::__construct();

        $this->parameters['terminalId'] = $terminalId;
        $this->parameters['password'] = $terminalId;
        $this->parameters['acceptorId'] = $acceptorId;
        $this->parameters['publicKey'] = $acceptorId;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);

        $provider->setExtraParameter('', $this->parameters['acceptorId'])
            ->setExtraParameter('', $this->parameters['terminalId']);

        $data = [];
        $data['request'] = $provider->getParameters();
        $data['authenticationEnvelope'] = IranKishUtil::generateAuthenticationEnvelope(
            $this->parameters['publicKey'],
            $this->parameters['terminalId'],
            $this->parameters['password'],
            $provider->getParameter('amount') ?? 0,
        );

        $result = $this->request($data, $this->urls['payment']);

        $resProvider = new IranKishRequestResultProvider($result['response']);
        if ($resProvider->getResponseCode() == '00') {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getResponseCode(),
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

        $resProvider = new IranKishHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (
            !is_null($resProvider->getToken()) &&
            $resProvider->getToken() != "" &&
            $resProvider->getResponseCode() == "00"
        ) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new IranKishAdviceProvider();
            $provider->setExtraParameter('terminalId', $this->parameters['terminalId'])
                ->setExtraParameter('retrievalReferenceNumber', $resProvider->getRetrievalReferenceNumber())
                ->setExtraParameter('systemTraceAuditNumber', $resProvider->getSystemTraceAuditNumber())
                ->setExtraParameter('tokenIdentity', $resProvider->getToken());

            $result = $this->request($provider->getParameters(), $this->urls['verify']);

            $adviceProvider = new IranKishAdviceResultProvider($result);
            if (
                !is_null($adviceProvider->getResponseCode()) &&
                $adviceProvider->getResponseCode() == '00' &&
                $adviceProvider->getStatus(false)
            ) {
                $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider, $resProvider]);
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $adviceProvider->getResponseCode(),
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
