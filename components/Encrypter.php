<?php

namespace app\components;

/**
 * Class Encrypter
 * Responsible for encrypting and decrypting strings with kidups public/private keyset
 * @package app\components
 */
class Encrypter
{
    const SIZE_512 = 512;
    const SIZE_1024 = 1024;
    const SIZE_2048 = 2048;
    const SIZE_5096 = 5096;

    /**
     * Encrypts a piece of data with a given keysize
     * @param string $data
     * @param int $key
     * @return bool|string
     */
    public static function encrypt($data, $key = self::SIZE_1024){
        $keyName = \Yii::$aliases['@app']."/devops/keys/public/kidup_public_".$key.'.pem';

        if(!is_file($keyName)){
            return false;
        }
        $file = file_get_contents($keyName);

        openssl_public_encrypt($data, $encrypted, $file);
        return base64_encode($encrypted);
    }

    /**
     * Decrypts a cryptedText
     * @param string $cryptoText
     * @param int $key
     * @return bool|string
     */
    public static function decrypt($cryptoText, $key){
        openssl_private_decrypt(base64_decode($cryptoText),$decrypt,$key);
        return $decrypt;
    }
}