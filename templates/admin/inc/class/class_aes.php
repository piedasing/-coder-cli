<?php
class class_aes {

    private static $_cipher = 'aes-256-gcm';
    private static $_key = AES_CRYPT_KEY;
    private static $_iv= AES_CRYPT_IV;

    function __construct() 
    {

    }
    public function encrypt($data)
    {
        // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$_cipher));
        $iv = self::$_iv;

        $encrypted = openssl_encrypt(json_encode($data), self::$_cipher, self::$_key, 0, $iv, $tag);
        // return base64_encode($encrypted . '::' . $iv. '::' . $tag);
        return base64_encode($encrypted . '::' . $tag);
    }

    public function decrypt($garble)
    {
        // list($encrypted_data, $iv, $tag) = array_pad(explode('::', base64_decode($garble), 3), 3, null);
        list($encrypted_data, $tag) = array_pad(explode('::', base64_decode($garble), 2), 2, null);
        if (!$tag) return false;
        $iv = self::$_iv;
        return openssl_decrypt($encrypted_data, self::$_cipher, self::$_key, 0, $iv, $tag);
    }

    // public static function encrypt($data) {
    //     $key_length = 32;
    //     $iv_length = openssl_cipher_iv_length(self::$_cipher);

    //     $key = self::getHashedStr(self::$_key, $key_length);
    //     $iv = self::getHashedStr(self::$_iv, $iv_length);

    //     $ciphertext_raw = openssl_encrypt(json_encode($data), self::$_cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);

    //     return base64_encode($iv . $ciphertext_raw . $tag);
    // }
    
    // public static function decrypt($token) {
    //     $key_length = 32;
    //     $iv_length = openssl_cipher_iv_length(self::$_cipher);
    //     $tag_length = 16;

    //     $key = self::getHashedStr(self::$_key, $key_length);
    //     $iv = self::getHashedStr(self::$_iv, $iv_length);

    //     $chiphertext = substr(base64_decode($token, 1), $iv_length, - $tag_length);
    //     $tag = substr(base64_decode($token, 1), - $tag_length);

    //     return openssl_decrypt($chiphertext, self::$_cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
    // }

    private static function getHashedStr($value, $length) {
        return substr(hash('sha256', $value, false), 0, $length);
    }

}
