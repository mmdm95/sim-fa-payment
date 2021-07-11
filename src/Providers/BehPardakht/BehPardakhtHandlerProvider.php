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
        $this->parameters['RefId'] = isset($data['RefId']) ? $data['RefId'] : null;
        $this->parameters['ResCode'] = isset($data['ResCode']) ? $data['ResCode'] : null;
        $this->parameters['SaleOrderId'] = isset($data['SaleOrderId']) ? $data['SaleOrderId'] : null;
        $this->parameters['SaleReferenceId'] = isset($data['SaleReferenceId']) ? $data['SaleReferenceId'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getRefId($prefer = null)
    {
        return isset($this->parameters['RefId']) ? $this->parameters['RefId'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getResCode($prefer = null)
    {
        return isset($this->parameters['ResCode']) ? $this->parameters['ResCode'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSaleOrderId($prefer = null)
    {
        return isset($this->parameters['SaleOrderId']) ? $this->parameters['SaleOrderId'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getSaleReferenceId($prefer = null)
    {
        return isset($this->parameters['SaleReferenceId']) ? $this->parameters['SaleReferenceId'] : $prefer;
    }
}