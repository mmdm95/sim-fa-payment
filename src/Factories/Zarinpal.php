<?php

namespace Sim\Payment\Factories;

use Sim\Event\Event;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\Zarinpal\ZarinpalHandlerProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalAdviceResultProvider;
use Sim\Payment\Providers\Zarinpal\ZarinpalRequestResultProvider;
use SoapClient as Soap;

class Zarinpal extends AbstractPayment
{
    // event constants
    const DUPLICATE_SEND_ADVICE = 'send-advice:duplicate';
    const FAILED_SEND_ADVICE = 'send-advice:failed';

    /**
     * @var string
     */
    protected $handlerMethod = PaymentFactory::METHOD_GET;

    /**
     * @var array
     */
    protected $code_message = [
        self::OPERATION_REQUEST => [
            -1 => 'اطلاعات ارسال شده ناقص است.',
            -2 => 'IP و یا مرچنت کد پذیرنده صحیح نیست.',
            -3 => 'با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد.',
            -4 => 'سطح تاييد پذيرنده پايين تر از سطح نقره اي است.',
            -11 => 'درخواست مورد نظر يافت نشد.',
            -12 => 'امكان ويرايش درخواست ميسر نمي باشد.',
            -21 => 'هيچ نوع عمليات مالي براي اين تراكنش يافت نشد.',
            -22 => 'تراكنش ناموفق ميباشد.',
            -33 => 'رقم تراكنش با رقم پرداخت شده مطابقت ندارد.',
            -34 => 'سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است',
            -40 => 'اجازه دسترسي به متد مربوطه وجود ندارد.',
            -41 => 'غيرمعتبر ميباشد. AdditionalData اطلاعات ارسال شده مربوط به',
            -42 => 'مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيقه تا 45 روز باشد.',
            -54 => 'درخواست مورد نظر آرشيو شده است.',
            100 => 'عمليات با موفقيت انجام گرديده است.',
            101 => 'تراكنش انجام شده است. PaymentVerification عمليات پرداخت موفق بوده و قبلا'
        ],
    ];

    /**
     * @var array
     */
    protected $urls = [
        'service_url' => 'https://www.zarinpal.com/pg/services/WebGate/wsdl',
        'payment' => 'https://www.zarinpal.com/pg/StartPay/',
    ];

    /**
     * Zarinpal constructor.
     * @param string $merchantId
     */
    public function __construct(string $merchantId)
    {
        parent::__construct();

        // extra events
        $this->event_provider->addEvent(new Event(self::DUPLICATE_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::FAILED_SEND_ADVICE));

        // URL also can be ir.zarinpal.com or de.zarinpal.com
        $this->client = new Soap($this->urls['service_url'], ['encoding' => 'UTF-8']);

        $this->parameters['MerchantID'] = $merchantId;
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
    public function failedSendAdviceClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::FAILED_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);

        $provider->setExtraParameter('MerchantID', $this->parameters['MerchantID']);
        $result = $this->client->PaymentRequest($provider->getParameters());
        $resProvider = new ZarinpalRequestResultProvider($result);

        if ($resProvider->getStatus() == 100) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(
                self::NOT_OK_CREATE_REQUEST, [
                    $resProvider->getStatus(),
                    $this->getMessage($resProvider->getStatus(), self::OPERATION_REQUEST),
                    $resProvider
                ]
            );
        }
        $this->emitter->dispatch(self::AF_CREATE_REQUEST, [$resProvider]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAdvice(AbstractAdviceParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_HANDLE_RESULT);

        $resProvider = new ZarinpalHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (!empty($resProvider->getStatus()) || !empty($resProvider->getAuthority())) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $amount = $provider->getParameter('Amount');
            if (empty($amount) || !is_numeric($amount)) {
                $this->emitter->dispatch(self::FAILED_SEND_ADVICE, [
                    -22,
                    'مبلغی برای احراز عملیات بانکی تعریف نشده است.',
                    $resProvider
                ]);
            }
            if (empty($resProvider->getAuthority())) {
                $this->emitter->dispatch(self::FAILED_SEND_ADVICE, [
                    -22,
                    'Authority برای احراز عملیات بانکی تعریف نشده است.',
                    $resProvider
                ]);
            }

            // add extra needed parameters to advice parameter provider
            $provider->setExtraParameter('MerchantID', $this->parameters['MerchantID'])
                ->setExtraParameter('Authority', $resProvider->getAuthority());

            if ('OK' == $resProvider->getStatus()) {
                $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

                $result = $this->client->PaymentVerification($provider->getParameters());

                $adviceProvider = new ZarinpalAdviceResultProvider($result);
                if ($adviceProvider->getStatus() == 100) {
                    $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider]);
                } else if ($adviceProvider->getStatus() == 101) {
                    $this->emitter->dispatch(self::DUPLICATE_SEND_ADVICE, [
                        101,
                        $this->getMessage(101, self::OPERATION_REQUEST),
                        $adviceProvider
                    ]);
                } else {
                    $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                        -22,
                        $this->getMessage(-22, self::OPERATION_REQUEST),
                        $adviceProvider,
                        $resProvider
                    ]);
                }

                $this->emitter->dispatch(self::AF_SEND_ADVICE, [$adviceProvider, $resProvider]);
            } else {
                $this->emitter->dispatch(self::FAILED_SEND_ADVICE, [
                    -22,
                    'تراکنش توسط کاربر لغو شد.',
                    $resProvider
                ]);
            }
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
