<?php

Class Upload {

  //4194304
  private static $_max_file_size = 4194304; // 4 MB (bytes);
  private static $_allowed_mime_types = ['image/png', 'image/jpg', 'image/jpeg'];
  private static $_allowed_extensions = ['png', 'jpg', 'jpeg'];


  public static function fnCheckFileSize($file){
    if(filesize($file) < self::$_max_file_size){
      return true;
    }else{
      return false;
    }
  }

/*
Check image mimetype
*/
  public static function fnCheckMimeType($file){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    return in_array(finfo_file($finfo, $file), self::$_allowed_mime_types);
  }

  public static function fnCheckFileExtension($file){
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);
    return(in_array(strtolower($file_ext), self::$_allowed_extensions));
  }

  /*
  * Check image size returns false, if it can't detect any width/height properties
  */
  public static function fnCheckIsImage($file){
    if(getimagesize($file)){
      return true;
    }else{
      return false;
    }
  }

} // end helper class upload



?>

