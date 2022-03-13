<?php
if(!isset($_SESSION)) { session_start(); }

class Session{

  private static $_sessionData = null;
  private static $_sessionExpireTime = 60 * 60; //seconds

  //constructer creates new session instance
  public static function fnSetUserSession($userData){ //takes in user object
    session_regenerate_id();

    self::$_sessionData = $userData;
    self::$_sessionData->is_logged_in = true;
    self::$_sessionData->ip_address = $_SERVER['REMOTE_ADDR'];
    self::$_sessionData->user_agent = $_SERVER['HTTP_USER_AGENT'];
    self::$_sessionData->last_login = time(); //unix time - seconds
    return self::$_sessionData;
  }

  /*
    Getter function, returns one value from the sessionData object
  */
    public static function fnGetSessionData($value){
      if(property_exists(self::$_sessionData, $value))
        return self::$_sessionData->$value;
      }

  /*
    Function checks whether is_logged_in is set and is true
  */
    public static function fnIsUserLoggedIn(){
      if(isset($_SESSION['user']) && $_SESSION['user']->is_logged_in){
        return true;
      }else{
        return false;
      }
    }

  /*
    Function checks whether the request ip adress and user agent are the same as when the session instance was created
  */
    private static function fnIsRequestValid(){
      return
      (isset($_SESSION['user']) &&
        $_SESSION['user']->ip_address === $_SERVER['REMOTE_ADDR'] &&
        $_SESSION['user']->user_agent === $_SERVER['HTTP_USER_AGENT']);
    }

  /*
    Function checks whether the session has expired, returns true if the session has NOT experied
  */
    private static function fnHasSessionExpired(){

      if($_SESSION['user']->last_login + self::$_sessionExpireTime > time()){
        return true;
      }else{
        return false;
      }

    }

    /*
      Public function used to check whether logged in user has premium status
    */
      public static function fnIsUserPremium(){
        session_regenerate_id();
        if($this->_sessionData->status === 'premium')
          return true;
        else
          return false;
      }

    /*
     Public function used to check access to endpoints where user must be authorized
    */
     public static function fnAuthenticateUser(){
      session_regenerate_id();
      if(self::fnIsUserLoggedIn() && self::fnIsRequestValid() && self::fnHasSessionExpired()){
        return true;
      }else{
        return false;
      }
    }

    /*
      Public function to destroy the session on logout
    */

      public static function fnLogoutUser(){
        return session_destroy();
      }

    }
    ?>

