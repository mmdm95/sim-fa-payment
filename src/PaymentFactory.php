<?php

namespace Sim\Payment;

use Sim\Payment\Factories\BehPardakht;
use Sim\Payment\Factories\IDPay;
use Sim\Payment\Factories\Mabna;
use Sim\Payment\Factories\Zarinpal;

class PaymentFactory
{
    // Method constants
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_CONNECT = 'CONNECT';

    // Gateways constants
    const GATEWAY_ID_PAY = 1;
    const GATEWAY_MABNA = 2;
    const GATEWAY_BEH_PARDAKHT = 3;
    const GATEWAY_ZARINPAL = 4;

    /**
     * @param int $type
     * @param mixed ...$data
     * @return BehPardakht|IDPay|Mabna|Zarinpal|null
     */
    public static function instance(int $type,  ...$data)
    {
        switch ($type) {
            case self::GATEWAY_ID_PAY:
                return new IDPay(...$data);
            case self::GATEWAY_MABNA:
                return new Mabna();
            case self::GATEWAY_BEH_PARDAKHT:
                return new BehPardakht(...$data);
            case self::GATEWAY_ZARINPAL:
                return new Zarinpal(...$data);
            default:
                return null;
        }
    }
}