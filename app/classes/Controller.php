<?php
/*
 * The base controller class, subclass for other controllers
 * Loads models and views
 */

class Controller {
  //load model and instanciate
  public function model($model){
    //require model file
    require_once '../app/models/'. $model .'.php';

    //instanciate model
    return new $model;
  }

  //load view, and pass in data (optional)
  public function view($view, $ajaxRequest, $data=[]){
    //if request is not ajax, then include header and navigation
    if(!$ajaxRequest){
      require_once APPROOT . '/views/includes/header.php';
      require_once APPROOT . '/views/includes/nav.php';
    }

    //always require the requested file
    if(file_exists('../app/views/'. $view . '.php')){
      require_once '../app/views/'. $view . '.php';
    }else{
        //die('View does not exist');
      //404 page
      //require_once '../app/views/404.php';
      echo 'View does not exist';
    }

    //if not ajax, include footer and scripts
    if(!$ajaxRequest){
      require_once APPROOT . '/views/includes/footer.php';
      require_once APPROOT . '/views/includes/scripts.php';
    }


  }
}

