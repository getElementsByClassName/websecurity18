<?php

/*
  Request class, gets URL and loads request controller
  URL format is /pages/method/params
*/
  class FrontController extends Controller {
    public $controller = 'Pages';
    public $method = 'index';
    public $params = [];
    public $httpMethod;
    public $ajaxRequest = false;
    public $postBody;
    public $url;
    public $post;

    public function __construct(){
      if(!isset($_SESSION)) { session_start(); }
      //set model
      $this->model = $this->model('User');

      //check if request comes from a blacklisted ip address
      //...

      //check if request is ajax (only used for front end UX)
      if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        $this->ajaxRequest = true;
      }

      //set the request method
      $this->httpMethod = $_SERVER['REQUEST_METHOD'];

      //parse the url query string
      $url = $this->fnGetUrl();

      //set controller and method
      $this->fnGetController($url);

      //if http method is a GET, instanciate appropiate controller and method
      if($this->httpMethod === 'GET'){
        $this->controller-> { $this->method }($this->ajaxRequest, $this->params);
      }

      //if http request method is POST, whitelist what methods/actions can be taken in app
      if($this->httpMethod === 'POST'){

        //method checks referer, if it exists and domain is the same as servers
       if(!$this->fnCheckReferer()){
        $this->model->fnBlacklistIp($_SERVER['REMOTE_ADDR']);
        exit;
      }

        //validate csrf token
      if(!(isset($_POST['token'])) || !(Token::fnDoesTokensMatch($_POST['token']))){

        echo '{"status" : "403" ,
        "message" : "Invalid request"}';
        exit;
      }


        //if user navigated to users/login

      if((isset($url[0]) && $url[0] === 'users') &&
        (isset($url[1]) && $url[1] === 'login')){

          //Sanitize user input
        $_POST = Sanitize::fnSanitizeArray(INPUT_POST, Sanitize::$filterSanitizeLoginForm);

          //Validate user input
      if(!(Validate::fnValidateInput($_POST, Validate::$filterValidateLoginForm) === true)){

        echo
        '{"status": "401",
        "message" : "Not Ok",
        "inputs" : '.Validate::fnValidateInput($_POST, Validate::$filterValidateLoginForm).'}';
        exit;
      }

        }//end if login


        //if user navigated to users/register

        if((isset($url[0]) && $url[0] === 'users') &&
         (isset($url[1]) && $url[1] === 'register')){

          //Sanitize user input
          $_POST = Sanitize::fnSanitizeArray(INPUT_POST, Sanitize::$filterSanitizeRegisterForm);

        //Validate user input
        if(!(Validate::fnValidateInput($_POST, Validate::$filterValidateRegisterForm) === true)){

          echo
          '{"status": "401",
          "message" : "Not Ok",
          "inputs" : '.Validate::fnValidateInput($_POST, Validate::$filterValidateRegisterForm).'}';
          exit;
        }

        //if passwords does not match
        if($_POST['password'] !== $_POST['password_confirm']){
          $aError = array('password_confirm');
          echo
          '{"status": "401",
          "message" : "Passwords does not match",
          "inputs" : '.json_encode($aError).'}';
          exit;
        }

        //validate google recaptcha
        $secretKey = "6LfSNF4UAAAAAFuNAcR-TjePg5fb2FouYf306gbY";


        $responseKey = isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : null;
        $userIp = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify";

        //set query string
        $post_data = http_build_query(
          array(
            'secret' => $secretKey,
            'response' => $responseKey,
            'remoteip' => $userIp
          )
        );

        //set headers and method
        $options = array('http' =>
          array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data
          )
        );

        //get respons from google
        $context  = stream_context_create($options);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

        //decode response to get object
        $response = json_decode($response);

        if(!$response->success){
          echo
          '{"status": "401",
          "message" : "Please verify that you are not a robot",
          "token":"'.Token::fnGenerateToken().'"}';
          exit;
        }
        }//end if register



        //if user navigated to images/upload (to create new album)

        if((isset($url[0]) && $url[0] === 'images') &&
          (isset($url[1]) && $url[1] === 'upload')){

          //Sanitize user input (album name)
          $_POST = Sanitize::fnSanitizeArray(INPUT_POST, Sanitize::$filterSanitizeUploadForm);

        }//end if images/upload



        //if user posted a comment
        if((isset($url[0]) && $url[0] === 'images') &&
          (isset($url[1]) && $url[1] === 'comment') &&
          (isset($url[2]) && ctype_xdigit($url[2]))){ //check id is a string hex

          //sanitize user input
         $_POST = Sanitize::fnSanitizeArray(INPUT_POST, Sanitize::$filterSanitizeCommentForm);

       $_POST['photo_id'] = $url[2];

      }//end if user posts comment*/


        //all POST values has been validatet by the frontController, pass on to the Controller
      $this->postBody = $_POST;
        //instanciate controller
      $this->controller-> { $this->method }($this->postBody);

      }//end if method POST

    }//end front controller constructor


/*
  function that parses the URL query
*/
  private function fnGetUrl(){
      if(isset($_GET['url'])){ //htaccess set to rewrite request url to index.php?url=, check if it's set

      $url = $_GET['url'];
        $url = rtrim($url, '/'); //remove optional slash at end
        $url = filter_var($url, FILTER_SANITIZE_URL); //url should be valid
        $url = filter_var($url, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); //Remove HTML tags and all characters with ASCII value > 127

        //explode by '/' and return array
        $url = explode('/', $url);

        return $url;
      }

    } //end method fnGetUrl


/*
  Sets the controller and method
*/
  private function fnGetController($url){

      //white list what controller can be loaded, else it will be default
    if(isset($url[0])){
      $controller = ucwords($url[0]);
      switch($controller){

        case "Pages":
        $this->controller = 'Pages';
        break;

        case "Users":
        $this->controller = 'Users';
        break;

        case "Images":
        $this->controller = 'Images';
        break;

      }
      unset($url[0]);
    }

      //require controller and instanciate class
    require_once '../app/controllers/' . $this->controller. '.php';
    $this->controller = new $this->controller;


      //check if method exist in url array
    if(isset($url[1])){
        $method = ucwords($this->httpMethod) . ucwords($url[1]); //e.g PostLogin, GetLogin
        // Check to see if method exists in controller
        if(method_exists($this->controller, $method)){
          $this->method = $method;
          // Unset 1 index
          unset($url[1]);
        }
      }

      //get params (if url array still has values add them to params, else params stays empty)
      $this->params = $url ? array_values($url) : [];
    } // end fnGetController


/*
  Check referer when a POST request is send
*/

  private function fnCheckReferer(){
      //check referer header, if the request comes from the same domain
      if(!isset($_SERVER['HTTP_REFERER'])){ //simply exit if the header doesn't exist, and blacklist
      return false;
    }

      //if referer exists, check if domain name is the same as servers own
    if(isset($_SERVER['HTTP_REFERER'])){
      //parse the hostname from the referer
      $refererDomain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
      $serverDomain = $_SERVER['HTTP_HOST'];

      if($refererDomain != $serverDomain){
        return false;
      }
      return true;
    }
  }


}//end class router

?>
