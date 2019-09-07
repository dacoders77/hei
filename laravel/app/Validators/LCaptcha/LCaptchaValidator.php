<?php

namespace App\Validators\LCaptcha;

use GuzzleHttp\Client;

class LCaptchaValidator
{
    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ){
        $data = json_decode($value);
        if(!$data) return false;
        $token = $data->t;
        unset($data->t);

        $data = $this->cryptoJsAesDecrypt($token,json_encode($data))['t'];

        if(!$data) return false;

        $data = explode(',', $data);
        unset($data[1]);
        unset($data[3]);
        $data = implode('', $data);

        $data = date('U') - intval($data);
        return $data < 70 && $data > 0;
    }

    /**
    * Decrypt data from a CryptoJS json encoding string
    *
    * @param mixed $passphrase
    * @param mixed $jsonString
    * @return mixed
    */
    private function cryptoJsAesDecrypt($passphrase, $jsonString){
        $jsondata = json_decode($jsonString, true);
        $salt = hex2bin($jsondata["s"]);
        $ct = base64_decode($jsondata["ct"]);
        $iv  = hex2bin($jsondata["iv"]);
        $concatedPassphrase = $passphrase.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
    }

}