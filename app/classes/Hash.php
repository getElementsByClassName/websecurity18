<?php
class Hash {

  private static $_peber = 'peber';
  //private static $_peber = 'BE7AFB75F192F2B72E8323C5126B8001';

  /*
    -The php password_hash function default uses the bcrypt hashing function (based on blow fish algorithm) as its default, at present
    -includes salt (randomness 10), which is recommended to use as of php 7.0
    -returns the algorithm, cost and salt as part of the returned hash
    -peber is a static value
  */
    public static function fnGenerateHash($sPassword){
      return password_hash($sPassword.self::$_peber, PASSWORD_DEFAULT);
    }

  /*
    -Verify that a given strings hash matches a stored hash, based on the password_hash function
  */
    public static function fnVerifyPassword($sPassword, $sHash){
      if(password_verify($sPassword.self::$_peber, $sHash))
        return true;
        else
          return false;
      }


}//end class Hash
?>
