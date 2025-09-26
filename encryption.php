<?php
require 'config.php';
$key = $_ENV['KEY'];
function encrypt($plaintext){
    global $key;
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $ciphertext = openssl_encrypt( $plaintext , 'aes-256-cbc' , $key , 0 , $iv );
    $encodedCipher = base64_encode($iv . $ciphertext);
    return $encodedCipher;
}

function decrypt($encodedCipher){
    global $key;
    $data = base64_decode($encodedCipher);
    $iv = substr( $data, 0, 16);
    $ciphertext = substr($data, 16);
    $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, 0, $iv);
    return $plaintext;
}

?>
