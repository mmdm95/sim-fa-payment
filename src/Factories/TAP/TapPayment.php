<?php

namespace Sim\Payment\Factories\TAP;

use Sim\Payment\Abstracts\AbstractAdviceParameterProvider;
use Sim\Payment\Abstracts\AbstractParameterProvider;
use Sim\Payment\PaymentFactory;
use Sim\Payment\Providers\CurlProvider;
use Sim\Payment\Providers\HeaderProvider;
use Sim\Payment\Providers\TAP\Payment\TapAdviceProvider;
use Sim\Payment\Providers\TAP\Payment\TapAdviceResultProvider;
use Sim\Payment\Providers\TAP\Payment\TapHandlerProvider;
use Sim\Payment\Providers\TAP\Payment\TapRequestResultProvider;
use Sim\Payment\Utils\PaymentCurlUtil;
use SoapClient as Soap;

class TapPayment extends AbstractTap
{
    /**
     * {@inheritdoc}
     */
    protected $urls = [
        'request_service_url' => 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?WSDL',
        'advice_service_url' => 'https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?WSDL',
    ];

    /**
     * {@inheritdoc}
     */
    protected $gateway_variables_name = [
        self::OPERATION_REQUEST => [

        ],
    ];

    /**
     * TapPayment constructor.
     * @param string $loginAccount
     */
    public function __construct(string $loginAccount)
    {
        parent::__construct();

        //----- Set credential info
        $this->parameters['LoginAccount'] = $loginAccount;
        //-----
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(AbstractParameterProvider $provider): void
    {
        $this->emitter->dispatch(self::BF_CREATE_REQUEST);

        $this->setRequestSoap();

        $provider->setExtraParameter('LoginAccount', $this->parameters['LoginAccount']);
        $result = $this->client->SalePaymentRequest([
            'requestData' => $provider->getParameters(),
        ]);
        //-----
        $result = json_decode(json_encode($result), true);
        $resProvider = new TapRequestResultProvider($result);

        if ($resProvider->getToken() && $resProvider->getStatus(-1) == 0) {
            $this->emitter->dispatch(self::OK_CREATE_REQUEST, [$resProvider]);
        } else {
            $this->emitter->dispatch(self::NOT_OK_CREATE_REQUEST, [
                $resProvider->getStatus(),
                $resProvider->getMessage(),
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

        $this->setAdviceSoap();

        $resProvider = new TapHandlerProvider($this->handleRequest($this->gateway_variables_name[self::OPERATION_REQUEST]));

        if (
            !is_null($resProvider->getRRN()) &&
            $resProvider->getRRN() > 0 &&
            !is_null($resProvider->getStatus()) &&
            $resProvider->getStatus() == 0
        ) {
            $this->emitter->dispatch(self::OK_HANDLE_RESULT, [$resProvider]);

            $this->emitter->dispatch(self::BF_SEND_ADVICE, [$resProvider]);

            $provider = new TapAdviceProvider();
            $provider->setExtraParameter('Token', $resProvider->getToken())
                ->setExtraParameter('LoginAccount', $this->parameters['LoginAccount']);

            $result = $this->client->ConfirmPayment([
                'requestData' => $provider->getParameters(),
            ]);

            $result = json_decode(json_encode($result), true);
            $adviceProvider = new TapAdviceResultProvider($result);
            if (!is_null($adviceProvider->getStatus()) && $adviceProvider->getStatus() == 0) {
                $this->emitter->dispatch(self::OK_SEND_ADVICE, [$adviceProvider, $resProvider]);
            } else {
                $this->emitter->dispatch(self::NOT_OK_SEND_ADVICE, [
                    $adviceProvider->getStatus(),
                    $adviceProvider->getMessage(),
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
     * @param array $data
     * @param string $url
     * @return mixed
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

    private function setRequestSoap()
    {
        $this->client = new Soap($this->urls['request_service_url'], ['encoding' => 'UTF-8']);
    }

    private function setAdviceSoap()
    {
        $this->client = new Soap($this->urls['advice_service_url'], ['encoding' => 'UTF-8']);
    }
}
