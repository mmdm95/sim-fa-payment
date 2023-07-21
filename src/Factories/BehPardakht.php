<?php

namespace Sim\Payment\Factories;

use Sim\Event\Event;
use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\Providers\BehPardakht\BehPardakhtAdviceProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtAdviceResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtHandlerProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtRequestResultProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtSettleProvider;
use Sim\Payment\Providers\BehPardakht\BehPardakhtSettleResultProvider;
use SoapClient as Soap;

class BehPardakht extends AbstractPayment
{
    // event constants
    const DUPLICATE_SEND_ADVICE = 'send-advice:duplicate';
    const BF_SEND_SETTLE = 'send-settle:bf';
    const AF_SEND_SETTLE = 'send-settle:af';
    const OK_SEND_SETTLE = 'send-settle:ok';
    const NOT_OK_SEND_SETTLE = 'send-settle:not-ok';

    /**
     * {@inheritdoc}
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            -1000 => 'خطا در ارتباط با درگاه پرداخت',
            0 => 'تراکنش با موفقيت انجام شد',
            11 => 'شماره کارت نامعتبر است',
            12 => 'موجودی کافي نيست',
            13 => 'رمز نادرست است',
            14 => 'تعداد دفعات وارد کردن رمز بيش از حد مجاز است',
            15 => 'کارت نامعتبر است',
            16 => 'دفعات برداشت وجه بيش از حد مجاز است',
            17 => 'کاربر از انجام تراکنش منصرف شده است',
            18 => 'تاريخ انقضای کارت گذشته است',
            19 => 'مبلغ برداشت وجه بيش از حد مجاز است',
            111 => 'صادر کننده کارت نامعتبر است',
            112 => 'خطای سوييچ صادر کننده کارت',
            113 => 'پاسخي از صادر کننده کارت دريافت نشد',
            114 => 'دارنده کارت مجاز به انجام اين تراکنش نيست',
            21 => 'پذيرنده نامعتبر است',
            23 => 'خطای امنيتي رخ داده است',
            24 => 'اطلاعات کاربری پذيرنده نامعتبر است',
            25 => 'مبلغ نامعتبر است',
            31 => 'پاسخ نامعتبر است',
            32 => 'فرمت اطلاعات وارد شده صحيح نمي باشد',
            33 => 'حساب نامعتبر است',
            34 => 'خطای سيستمي',
            35 => 'تاريخ نامعتبر است',
            41 => 'شماره درخواست تکراری است',
            42 => 'تراکنش Sale يافت نشد',
            43 => 'قبلا درخواست Verify داده شده است',
            44 => 'درخواست Verify يافت نشد',
            45 => 'تراکنش Settle شده است',
            46 => 'تراکنش Settle نشده است',
            47 => 'تراکنش Settle يافت نشد',
            48 => 'تراکنش Reverse شده است',
            412 => 'شناسه قبض نادرست است',
            413 => 'شناسه پرداخت نادرست است',
            414 => 'سازمان صادر کننده قبض نامعتبر است',
            415 => 'زمان جلسه کاری به پايان رسيده است',
            416 => 'خطا در ثبت اطلاعات',
            417 => 'شناسه پرداخت کننده نامعتبر است',
            418 => 'اشکال در تعريف اطلاعات مشتری',
            419 => 'تعداد دفعات ورود اطلاعات از حد مجاز گذشته است',
            421 => 'IP نامعتبر است',
            51 => 'تراکنش تکراری است',
            54 => 'تراکنش مرجع موجود نيست',
            55 => 'تراکنش نامعتبر است',
            61 => 'خطا در واريز',
            62 => 'مسير بازگشت به سايت در دامنه ثبت شده برای پذيرنده قرار ندارد',
            98 => 'سقف استفاده از رمز ايستا به پايان رسيده است',
        ],
    ];

    /**
     * @var string $namespace
     */
    protected $namespace = 'http://interfaces.core.sw.bps.com/';

    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'service_url' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
        'payment' => 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'RefId',
            'ResCode',
            'SaleOrderId',
            'SaleReferenceId',
            'CardHolderPAN',
            'CreditCardSaleResponseDetail',
            'FinalAmount',
        ],
    ];

    /**
     * BehPardakht constructor.
     * @param string $terminalId - Usually numeric value
     * @param string $username
     * @param string $password
     */
    public function __construct(string $terminalId, string $username, string $password)
    {
        parent::__construct();

        // extra events
        $this->event_provider->addEvent(new Event(self::DUPLICATE_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::BF_SEND_SETTLE));
        $this->event_provider->addEvent(new Event(self::AF_SEND_SETTLE));
        $this->event_provider->addEvent(new Event(self::OK_SEND_SETTLE));
        $this->event_provider->addEvent(new Event(self::NOT_OK_SEND_SETTLE));

        // connect to service
        $this->client = new Soap($this->urls['service_url'], ['encoding' => 'UTF-8']);

        //----- Set credential info
        $this->parameters['terminalId'] = $terminalId;
        $this->parameters['userName'] = $username;
        $this->parameters['userPassword'] = $password;
        //-----
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function duplicateSendAdviceClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::DUPLICATE_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function beforeSendSettleClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::BF_SEND_SETTLE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function afterSendSettleClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::AF_SEND_SETTLE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function sendSettleOKClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::OK_SEND_SETTLE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function sendSettleNotOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::NOT_OK_SEND_SETTLE, $closure);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);

        // set credentials
        $provider->setExtraParameter('terminalId', $this->parameters['terminalId'])
            ->setExtraParameter('userName', $this->parameters['userName'])
            ->setExtraParameter('userPassword', $this->parameters['userPassword']);

        $result = $this->client->__soapCall('bpPayRequest', [
            'parameters' => $provider->getParameters(),
            'namespace' => $this->namespace,
        ]);
        //-----
        $refId = null;
        $resCode = null;
        if (!is_soap_fault($result)) {
            $result = explode(',', $result->return);
            if (2 == count($result)) {
                $resCode = $result[0];
                $refId = $result[1] ?? '';
            }
        }
        //-----
        $resProvider = new BehPardakhtRequestResultProvider([
            'RefId' => $refId,
            'ResCode' => $resCode,
        ]);

        if ($resProvider->getResCode(-1) == 0) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getResCode(),
                !is_null($resProvider->getResCode())
                    ? $this->getMessage($resProvider->getResCode(), self::OPERATION_REQUEST)
                    : 'خطای نامشخص',
                $resProvider
            ]);
        }
        $this->emitter->dispatch(self::AF_CREATE_REQUEST, [$resProvider]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAdvice(AbstractAdviceParameterProvider $provider = null): void
    {
        $this->emitter->dispatch(self::BF_HANDLE_RESULT);

        $resProvider = new BehPardakhtHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (
            !is_null($resProvider->getRefId()) &&
            $resProvider->getResCode(-1000) != -1000 &&
            !is_null($resProvider->getSaleOrderId())
        ) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new BehPardakhtAdviceProvider();
            $provider->setExtraParameter('orderId', $resProvider->getSaleOrderId())
                ->setExtraParameter('saleOrderId', $resProvider->getSaleOrderId())
                ->setExtraParameter('saleReferenceId', $resProvider->getSaleReferenceId());

            // set credentials
            $provider->setExtraParameter('terminalId', $this->parameters['terminalId'])
                ->setExtraParameter('userName', $this->parameters['userName'])
                ->setExtraParameter('userPassword', $this->parameters['userPassword']);

            $result = $this->client->__soapCall('bpVerifyRequest', [
                'parameters' => $provider->getParameters(),
                'namespace' => $this->namespace,
            ]);
            //-----
            $result = json_decode(json_encode($result), true);
            $adviceResProvider = new BehPardakhtAdviceResultProvider($result);
            if ($adviceResProvider->getReturn(-1000) == 0 || $adviceResProvider->getReturn() == 51) {
                if ($adviceResProvider->getReturn(-1000) == 0) {
                    $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceResProvider, $resProvider]);

                    $this->emitter->dispatch(self::BF_SEND_SETTLE, [$adviceResProvider, $resProvider]);

                    $settleProvider = new BehPardakhtSettleProvider();
                    $settleProvider->setExtraParameter('orderId', $resProvider->getSaleOrderId())
                        ->setExtraParameter('saleOrderId', $resProvider->getSaleOrderId())
                        ->setExtraParameter('saleReferenceId', $resProvider->getSaleReferenceId());

                    // set credentials
                    $settleProvider->setExtraParameter('terminalId', $this->parameters['terminalId'])
                        ->setExtraParameter('userName', $this->parameters['userName'])
                        ->setExtraParameter('userPassword', $this->parameters['userPassword']);

                    // Settle request
                    $result = $this->client->__soapCall('bpSettleRequest', [
                        'parameters' => $settleProvider->getParameters(),
                        'namespace' => $this->namespace,
                    ]);

                    $settleResProvider = new BehPardakhtSettleResultProvider($result);

                    if ($settleResProvider->getReturn(-1000) == 0) {
                        $this->emitter->dispatch(self::OK_SEND_SETTLE, [
                            $settleResProvider,
                            $adviceResProvider,
                            $resProvider
                        ]);
                    } else {
                        $this->emitter->dispatch(self::NOT_OK_SEND_SETTLE, [
                            $settleResProvider->getReturn(),
                            $this->getMessage($settleResProvider->getReturn(), self::OPERATION_REQUEST),
                            $settleResProvider,
                            $adviceResProvider,
                            $resProvider
                        ]);
                    }
                    $this->emitter->dispatch(self::AF_SEND_SETTLE, [
                        $settleResProvider,
                        $adviceResProvider,
                        $resProvider
                    ]);
                } else {
                    $this->emitter->dispatch(self::DUPLICATE_SEND_ADVICE, [$adviceResProvider]);
                }
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $result['return'],
                    $this->getMessage($result['return'], self::OPERATION_REQUEST),
                    $adviceResProvider,
                    $resProvider
                ]);
            }
            $this->emitter->dispatch(self::AF_SEND_ADVICE, [$adviceResProvider, $resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_HANDLE_RESULT, [$resProvider]);
        }
        $this->emitter->dispatch(self::AF_HANDLE_RESULT, [$resProvider]);
    }

    /**
     * @param array $data
     * @param string $url
     * @return mixed
     */
    protected function request(array $data, string $url)
    {
        return null;
    }
}
