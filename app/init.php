<?php
//require config file
require_once '../app/config/config.php';

//autoload classes

spl_autoload_register(function($className){
  require_once 'classes/'.$className.'.php';
});


?>
