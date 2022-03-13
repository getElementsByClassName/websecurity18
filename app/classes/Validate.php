<?php
class Validate
{
  //filters for login form
  public static $filterValidateLoginForm = array(

    'email' => array('filter'  => FILTER_VALIDATE_EMAIL),

    'password' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$^/')
    )
  );

  public static $filterValidateRegisterForm = array(

    'email' => array('filter'  => FILTER_VALIDATE_EMAIL),

    'first_name' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/^[a-zæøåA-ZÆØÅ\s]+$/')
    ),

    'last_name' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/^[a-zæøåA-ZÆØÅ\s]+$/')
    ),

    'password' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/')
    ),
    'password_confirm' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/')
    ),
    'g-recaptcha-response' => array('filter'  => FILTER_VALIDATE_REGEXP,
      'options' => array('regexp' => '/^[\w-]*$/')
    )
  );

/*
 Validate input and output error array, with inputs not passing the validation
*/
 public static function fnValidateInput($inputValues, $filter){
  $validatedInput = (filter_var_array($inputValues, $filter));

  $aErrors = [];

  foreach ($validatedInput as $key => $value) {
    if(empty($value)){
      array_push($aErrors, $key);
    }
  }

  if(empty($aErrors)) return true;
      else return json_encode($aErrors); //return $aErrors, encode to json (for front end output);

    }

  }

  ?>



