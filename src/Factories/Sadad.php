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
                    "{$this->parameters['TerminalId']}{$provider->getParameter('OrderId')}{$provider->getParameter('Amount')}",
                    $this->parameters['key']
                )
            );
        $result = $this->request($provider->getParameters(), $this->urls['payment']);
        $resProvider = new SadadRequestResultProvider($result['response']);

        if ($resProvider->getResCode(-1) == 0) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [$resProvider->getResCode(), $resProvider->getDescription()]);
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

        if (!empty($resProvider->getOrderId()) && !empty($resProvider->getToken()) &&
            !empty($resProvider->getResCode()) && $resProvider->getResCode(-1) == 0) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE);

            $provider = new SadadAdviceProvider();
            $provider->setExtraParameter('Token', $resProvider->getToken())
                ->setExtraParameter('SignData', SadadUtil::encryptPkcs7($resProvider->getToken(), $this->parameters['key']));

            $result = $this->request($provider->getParameters(), $this->urls['verify']);

            $adviceProvider = new SadadAdviceResultProvider($result['response']);
            if ($adviceProvider->getResCode(-1) == 0) {
                $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider]);
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [$adviceProvider->getResCode(), 'تراکنش نا موفق بود در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برمی گردد.']);
            }
            $this->emitter->dispatch(self::AF_SEND_ADVICE, [$adviceProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_HANDLE_RESULT);
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
