<?php

class Encrypt {

  //set encryption mode
  private static $_cipherMethod = 'aes-256-cbc';


  /*
    Method that encrypts a string with given alg, the encryption key is saved in config file
  */
    public static function fnEncrypt($string){
      $iv = self::fnCreateInitVector();
      $cipherString = openssl_encrypt($string, self::$_cipherMethod, base64_decode(ENC_KEY), $options=0, $iv);
      $iv = base64_encode($iv);
      return array($cipherString, $iv);
    }

  /*
    Method that decrypts a cipherstring, takes in cipherstring and initVector, both saved in comments table in database
  */
    public static function fnDecrypt($cipherString, $initVector){
      return openssl_decrypt($cipherString, self::$_cipherMethod, base64_decode(ENC_KEY), $options=0, base64_decode($initVector));
    }

  //determine init vector length
    private static function fnCreateInitVectorLength(){
      return openssl_cipher_iv_length(self::$_cipherMethod);
    }

  //create an init vector
    private static function fnCreateInitVector(){
      return openssl_random_pseudo_bytes(self::fnCreateInitVectorLength());
    }

}//end class Encrypt


?>

