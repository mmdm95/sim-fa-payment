<?php

namespace Sim\Payment\Providers\BehPardakht;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class BehPardakhtHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * BehPardakhtHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['RefId'] = $data['RefId'] ?? null;
        $this->parameters['ResCode'] = $data['ResCode'] ?? null;
        $this->parameters['SaleOrderId'] = $data['SaleOrderId'] ?? null;
        $this->parameters['SaleReferenceId'] = $data['SaleReferenceId'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRefId($prefer = null)
    {
        return $this->parameters['RefId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return $this->parameters['ResCode'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSaleOrderId($prefer = null)
    {
        return $this->parameters['SaleOrderId'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSaleReferenceId($prefer = null)
    {
        return $this->parameters['SaleReferenceId'] ?? $prefer;
    }
}
