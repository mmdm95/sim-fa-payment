<?php

namespace Sim\Payment\Factories;

use Sim\Payment\Abstracts\AbstractPayment;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Utils\Curl;
use SoapClient as Soap;

class BehPardakht extends AbstractPayment
{
    // operation constants
    const OPERATION_REQUEST = 'request';
    const OPERATION_VERIFY = 'verify';

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
     * @param string|null $terminal_id
     * @param string|null $username
     * @param string|null $password
     */
    public function __construct(string $terminal_id = null, string $username = null, string $password = null)
    {
        // connect to service
        $this->client = new Soap($this->urls['service_url'], ['encoding' => 'UTF-8']);

        //----- Set credential info
        if (is_numeric($terminal_id)) {
            $this->setParameter('terminalId', $terminal_id);
        }
        if (!empty($username)) {
            $this->setParameter('userName', $username);
        }
        if (!empty($password)) {
            $this->setParameter('userPassword', $password);
        }
        //-----
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
        $data = $this->getParameters();

        $result = $this->client->__soapCall('bpPayRequest', [
            'parameters' => $data,
            'namespace' => $this->namespace,
        ]);

        $res = new \stdClass();
        $res->ResCode = null;
        $res->RefId = null;
        $res->Result = $result;

        if (!is_soap_fault($result)) {
            $result = explode(',', $result);
            if (2 == count($result)) {
                $res->ResCode = $result[0];
                $res->RefId = $result[1] ?? '';
            }
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function sendAdvice()
    {
        $data = $this->getParameters();
        // Check request
        return $this->client->__soapCall('bpVerifyRequest', [
            'parameters' => $data,
            'namespace' => $this->namespace,
        ]);
    }

    /**
     * @return mixed
     */
    public function settleRequest()
    {
        $data = $this->getParameters();
        // Settle request
        return $this->client->__soapCall('bpSettleRequest', [
            'parameters' => $data,
            'namespace' => $this->namespace,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function resetParameters()
    {
        $terminal_id = $this->getParameter('terminalId');
        $username = $this->getParameter('userName');
        $password = $this->getParameter('userPassword');

        // call parent reset
        parent::resetParameters();

        $this->setParameter('terminalId', $terminal_id);
        $this->setParameter('userName', $username);
        $this->setParameter('userPassword', $password);
    }
}