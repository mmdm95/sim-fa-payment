<?php

namespace Sim\Payment\Providers\IDPay;

use Sim\Payment\Abstracts\AbstractBaseParameterProvider;

class IDPayHandlerProvider extends AbstractBaseParameterProvider
{
    /**
     * IDPayHandlerProvider constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameters['status'] = $data['status'] ?? null;
        $this->parameters['track_id'] = $data['track_id'] ?? null;
        $this->parameters['id'] = $data['id'] ?? null;
        $this->parameters['order_id'] = $data['order_id'] ?? null;
        $this->parameters['amount'] = $data['amount'] ?? null;
        $this->parameters['card_no'] = $data['card_no'] ?? null;
        $this->parameters['hashed_card_no'] = $data['hashed_card_no'] ?? null;
        $this->parameters['date'] = $data['date'] ?? null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return $this->parameters['status'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTrackId($prefer = null)
    {
        return $this->parameters['track_id'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getId($prefer = null)
    {
        return $this->parameters['id'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return $this->parameters['order_id'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return $this->parameters['amount'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNO($prefer = null)
    {
        return $this->parameters['card_no'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getHashedCardNO($prefer = null)
    {
        return $this->parameters['hashed_card_no'] ?? $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDate($prefer = null)
    {
        return $this->parameters['date'] ?? $prefer;
    }
}
