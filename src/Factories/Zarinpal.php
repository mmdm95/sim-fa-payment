<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\Exceptions\PaymentException;
use Sim\Payment\Utils\Curl;
use SoapClient as Soap;

class Zarinpal extends AbstractPayment
{
    // operation constants
    const OPERATION_REQUEST = 'request';
    const OPERATION_VERIFY = 'verify';

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    protected $urls = [
        'service_url' => 'https://www.zarinpal.com/pg/services/WebGate/wsdl',
        'payment' => 'https://www.zarinpal.com/pg/StartPay/',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [
            'Authority',
            'Status',
        ],
    ];

    /**
     * Zarinpal constructor.
     * @param string|null $merchantID
     */
    public function __construct(string $merchantID = null)
    {
        // URL also can be ir.zarinpal.com or de.zarinpal.com
        $this->client = new Soap($this->urls['service_url'], ['encoding' => 'UTF-8']);

        if (!empty($merchantID)) {
            $this->setParameter('MerchantID', $merchantID);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest()
    {
        $result = [];
        foreach ($this->gateway_variables_name[self::OPERATION_REQUEST] as $name) {
            ${$name} = isset($_GET[$name]) ? Curl::escapeData($_GET[$name]) : null;
            $result[$name] = ${$name};
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest()
    {
        return $this->client->PaymentRequest($this->getParameters());
    }

    /**
     * {@inheritdoc}
     * @throws PaymentException
     */
    public function sendAdvice()
    {
        $amount = $this->getParameter('Amount');
        $authority = $this->getParameter('Authority');

        if (empty($amount) || !is_numeric($amount)) {
            throw new PaymentException('مبلغی برای احراز عملیات بانکی تعریف نشده است.');
        }
        if (empty($authority)) {
            throw new PaymentException('Authority برای احراز عملیات بانکی تعریف نشده است.');
        }

        $result = new \stdClass();
        $result->Message = 'تراکنش توسط کاربر لغو شد.';

        if ('OK' == $this->getParameter('Status')) {
            $result = $this->client->PaymentVerification([
                'MerchantID' => $this->getParameter('MerchantID'),
                'Authority' => $authority,
                'Amount' => $amount,
            ]);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function resetParameters()
    {
        // Get MerchantID
        $merchantID = $this->getParameter('MerchantID');

        // call parent reset
        parent::resetParameters();

        $this->setParameter('MerchantID', $merchantID);
    }
}