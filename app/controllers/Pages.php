<?php

class Pages extends Controller {
  public function __construct(){
    //if(!isset($_SESSION)) { session_start(); }
  }

  public function index($ajaxRequest){
    $this->view('pages/index', $ajaxRequest);
    //echo 'hello';
  }

  public function GetHome($ajaxRequest){
    $this->view('pages/home', $ajaxRequest);
  }

}

?>
