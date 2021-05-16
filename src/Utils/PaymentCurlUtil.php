<?php

namespace Sim\Payment\Utils;

use Sim\Payment\Providers\CurlProvider;

class PaymentCurlUtil
{
    /**
     * @param CurlProvider $curl_provider
     * @return array in following format
     * [
     *   'error' => curl error code,
     *   'message' => curl error message,
     *   'response' => curl response,
     * ]
     */
    public static function request(CurlProvider $curl_provider)
    {
        // open curl
        $curl_provider->init();
        // curl options
        $curl_provider->setOptionArray();
        // execute curl
        $curl_provider->execute();

        // decode executed curl to an object
        $response = json_decode($curl_provider->getResponse(), true);

        if ($curl_provider->getErrorNO() != 0) {
            $response = [];
        }

        $error = $curl_provider->getErrorNO();
        $message = $curl_provider->getError();

        // close curl resource
        $curl_provider->close();

        return [
            'error' => $error,
            'message' => $message,
            'response' => $response,
        ];
    }

    /**
     * Escape sent data from bank gateway to protect returned data
     *
     * @param $data
     * @return string
     */
    public static function escapeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
}
