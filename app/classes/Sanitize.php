<?php

class Sanitize
{
  public static $filterSanitizeLoginForm = array(
    'password' => array(
      'filter'=> FILTER_SANITIZE_STRING,
      'flags'=> FILTER_FLAG_STRIP_HIGH
    ),
    'email' => array(
      'filter' => FILTER_SANITIZE_EMAIL
    )
  );

  public static $filterSanitizeRegisterForm = array(
    'password' => array(
      'filter'=> FILTER_SANITIZE_STRING,
      'flags'=> FILTER_FLAG_STRIP_HIGH
    ),
    'password_confirm' => array(
      'filter'=> FILTER_SANITIZE_STRING,
      'flags'=> FILTER_FLAG_STRIP_HIGH
    ),
    'email' => array(
      'filter' => FILTER_SANITIZE_EMAIL
    ),
    'first_name' => array(
      'filter' => FILTER_SANITIZE_STRING
    ),
    'last_name' => array(
      'filter' => FILTER_SANITIZE_STRING
    ),
    'g-recaptcha-response' => array(
      'filter'=> FILTER_SANITIZE_STRING,
      'flags'=> FILTER_FLAG_STRIP_LOW
    ),
  );

  public static $filterSanitizeUploadForm = array(
    'album_name' => array(
      'filter'=> FILTER_SANITIZE_STRING,
      'flags'=> FILTER_FLAG_STRIP_HIGH
    ),
    'MAX_FILE_SIZE' => array(
      'filter'=> FILTER_SANITIZE_NUMBER_INT
    )
  );

  public static $filterSanitizeCommentForm = array(
    'comment' => array(
      'filter'=> FILTER_SANITIZE_SPECIAL_CHARS
    )
  );

  public static function fnSanitizeArray($aArray, $filters){
    return filter_input_array($aArray, $filters);
  }

}

?>

