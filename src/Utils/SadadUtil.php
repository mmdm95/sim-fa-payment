<?php

namespace Sim\Payment\Utils;

class SadadUtil
{
    /**
     * Create sign data(Tripledes(ECB,PKCS7))
     *
     * @param $str
     * @param $key
     * @return string
     */
    public static function encryptPkcs7($str, $key)
    {
        $key = base64_decode($key);
        $cipherText = OpenSSL_encrypt($str, "DES-EDE3", $key, OPENSSL_RAW_DATA);
        return base64_encode($cipherText);
    }
}
