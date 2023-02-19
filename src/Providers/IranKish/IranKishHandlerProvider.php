<?php

namespace Sim\Payment\Providers\IranKish;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IranKishHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * IranKishHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['token'] = $data['token'] ?? null;
        $this->parameters['responseCode'] = $data['responseCode'] ?? null;
        $this->parameters['retrievalReferenceNumber'] = $data['retrievalReferenceNumber'] ?? null;
        $this->parameters['systemTraceAuditNumber'] = $data['systemTraceAuditNumber'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getToken($prefer = null)
    {
        return $this->parameters['token'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResponseCode($prefer = null)
    {
        return $this->parameters['responseCode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRetrievalReferenceNumber($prefer = null)
    {
        return $this->parameters['retrievalReferenceNumber'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSystemTraceAuditNumber($prefer = null)
    {
        return $this->parameters['systemTraceAuditNumber'] ?? $prefer;
    }
}
