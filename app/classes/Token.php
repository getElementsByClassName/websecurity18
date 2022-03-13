<?php

Class Token{

  private static $_tokenExpireTime = 60*15;

  public static function fnGenerateToken() {
    self::fnDestroyToken();

    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['token_generated_at'] = time();
    return $_SESSION['token'];
  }

  private static function fnDestroyToken(){
    unset($_SESSION['token']);
  }

  /*
  Check if token from form matches the one from form
  */
  public static function fnDoesTokensMatch($token){
    return (($token == $_SESSION['token']) && ($_SESSION['token_generated_at'] + self::$_tokenExpireTime) > time());
  }
}
?>
