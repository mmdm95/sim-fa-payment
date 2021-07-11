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
        $this->parameters['status'] = isset($data['status']) ? $data['status'] : null;
        $this->parameters['track_id'] = isset($data['track_id']) ? $data['track_id'] : null;
        $this->parameters['id'] = isset($data['id']) ? $data['id'] : null;
        $this->parameters['order_id'] = isset($data['order_id']) ? $data['order_id'] : null;
        $this->parameters['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->parameters['card_no'] = isset($data['card_no']) ? $data['card_no'] : null;
        $this->parameters['hashed_card_no'] = isset($data['hashed_card_no']) ? $data['hashed_card_no'] : null;
        $this->parameters['date'] = isset($data['date']) ? $data['date'] : null;
        $this->addExtraParameters($data);
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getStatus($prefer = null)
    {
        return isset($this->parameters['status']) ? $this->parameters['status'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getTrackId($prefer = null)
    {
        return isset($this->parameters['track_id']) ? $this->parameters['track_id'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getId($prefer = null)
    {
        return isset($this->parameters['id']) ? $this->parameters['id'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getOrderId($prefer = null)
    {
        return isset($this->parameters['order_id']) ? $this->parameters['order_id'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getAmount($prefer = null)
    {
        return isset($this->parameters['amount']) ? $this->parameters['amount'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getCardNO($prefer = null)
    {
        return isset($this->parameters['card_no']) ? $this->parameters['card_no'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getHashedCardNO($prefer = null)
    {
        return isset($this->parameters['hashed_card_no']) ? $this->parameters['hashed_card_no'] : $prefer;
    }

    /**
     * @param $prefer
     * @return mixed
     */
    public function getDate($prefer = null)
    {
        return isset($this->parameters['date']) ? $this->parameters['date'] : $prefer;
    }
}