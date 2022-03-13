<?php

class Users extends Controller{
  public function __construct(){
    if(!isset($_SESSION)) { session_start(); }
    $this->userModel = $this->model('User');
  }

  public function index(){
    //default view
    $this->view('pages/home', $ajaxRequest);
  }

  public function GetLogin($ajaxRequest){
    $this->view('users/login', $ajaxRequest);
  }

  /*
    Method that LOGIN/authenticate a user
  */

    public function PostLogin($postBody){

    //post data have been sanitized+validated in frontcontroller
      $userData = $this->userModel->fnLoginUser($postBody['email'], $postBody['password']);

      if(!$userData){
        $this->userModel->fnUpdateLoginAttempts($postBody['email']);

        echo
        '{"status" : "401",
        "message" : "Your password and e-mail does not match"}';
        exit;

      }

      if($userData){
        $dataUserBanned = $this->userModel->fnIsUserBanned($postBody['email']);

        if($dataUserBanned[0]->timeDiff < 10 && $dataUserBanned[0]->login_attempts >3){
          $timeBanned = 10 - $dataUserBanned[0]->timeDiff;
          echo
          '{"status" : "401",
          "message": " You are banned for: '.$timeBanned.' more minutes"}';
          exit;
        }

        //else user is okay, login success
        //set login attempts to 0
        $this->userModel->fnClearLoginAttempts($postBody['email']);


        //create session with user data
        unset($userData[0]->pass);
        $_SESSION['user'] = Session::fnSetUserSession($userData[0]);


        //create session with folder paths that the user can access
        $ownAlbumFolders = $this->userModel->fnGetUsersFolders($_SESSION['user']->user_id);
        $sharedAlbumFolders = $this->userModel->fnGetUsersSharedFolders($_SESSION['user']->user_id);


        //turn into array of strings
        $aOwnAlbumFolders = array();
        $aSharedAlbumFolders = array();

        foreach($ownAlbumFolders as $key=>$value){
          array_push($aOwnAlbumFolders, $value->file_path);
        }

        foreach($sharedAlbumFolders as $key=>$value){
          array_push($aSharedAlbumFolders, $value->file_path);
        }

        //save array into a session that can be used as a reference for upload folder access control
        $_SESSION['album_folder_paths'] = $aOwnAlbumFolders;

        $_SESSION['shared_album_folder_paths'] = $aSharedAlbumFolders;


        echo
        '{"status" : "200",
        "message" : "You are logged in! "}';

      }

    }



    public function PostRegister($postBody){

      //postBody is sanitized/validated data coming in from the frontcontroller
      $postBody['password'] = Hash::fnGenerateHash($postBody['password']);

      if($this->userModel->fnRegisterUser($postBody['first_name'], $postBody['last_name'], $postBody['email'], $postBody['password'])){
        echo
        '{"status" : "200",
        "message" : "User sign up succesful"}';
        exit;
      }else{
       echo
       '{"status" : "401",
       "message" : "E-mail has to be unique"}';
       exit;
     }
   }

/*
  Get the register page
*/

  public function GetRegister($ajaxRequest){
    //return view register
    $this->view('users/register', $ajaxRequest);
  }

/*
  Get the profile page
*/

  public function GetProfile($ajaxRequest){

    //authenticate user
    if(!Session::fnAuthenticateUser()){
      session_destroy();
      header('Location: '. URLROOT . 'users/login');
      exit;
    }

    $data = [
      $user_albums = $this->userModel->fnGetUserAlbums($_SESSION['user']->user_id),
      $user_shared_albums = $this->userModel->fnGetUserSharedAlbums($_SESSION['user']->user_id)
    ];

    $this->view('users/profile', $ajaxRequest, $data);


  }

/*
  Get the an album
*/

  public function GetAlbum($ajaxRequest, $params){

    //redirect if user is not authenticated
    if(!Session::fnAuthenticateUser()){
      session_destroy();
      header('Location: '. URLROOT . 'users/login');
      exit;
    }

    //check album id passed from params(array) (ajax get request from UI)
    //album id is always a string pepresentation of a hexadecimal digit
    if(ctype_xdigit($params[0])){
      //fetch data from model
      $data = $this->userModel->fnGetAlbumPhotos($_SESSION['user']->user_id, $params[0]);

      if($data){
        //if user has access to album folder
        if(in_array($data[0]->folder_path, $_SESSION['album_folder_paths'])){

        //set view
          $this->view('users/album', $ajaxRequest, $data);

        }else{ //no access to album folder
          $this->view('pages/403', $ajaxRequest);
        }

      }else{ //no data returned
        $this->view('pages/403', $ajaxRequest);
      }

    }else{ //not a valid param
      //no access to album folder
      $this->view('pages/403', $ajaxRequest);

    }
  } // end method GetAlbum


  public function GetSharedalbum($ajaxRequest, $params){

   //redirect if user is not authenticated
    if(!Session::fnAuthenticateUser()){
      session_destroy();
      header('Location: '. URLROOT . 'users/login');
      exit;
    }

    //check album id passed from params(array) (ajax get request from UI)
    //album id is always a string pepresentation of a hexadecimal digit
    if(ctype_xdigit($params[0])){
      //fetch data from model
      $data = $this->userModel->fnGetSharedAlbumPhotos($_SESSION['user']->user_id, $params[0]);

      if($data){
        //if user has access to shared album folder
        if(in_array($data[0]->folder_path, $_SESSION['shared_album_folder_paths'])){

        //set view
          $this->view('users/album', $ajaxRequest, $data);

        }else{
          //no access to album folder
          $this->view('pages/403', $ajaxRequest);

        }

      }
    }else{
      //no access to album folder
      $this->view('pages/403', $ajaxRequest);

    }


}// end method GetSharedAlbum


/*
Method that shows single photo, with comments
*/
public function GetPhoto($ajaxRequest, $params){
  //redirect if user is not authenticated
  if(!Session::fnAuthenticateUser()){
    session_destroy();
    header('Location: '. URLROOT . 'users/login');
    exit;
  }

  //check ID from $params

  $data = $this->userModel->fnFetchPhoto($params[0]);

  $this->view('users/photo', $ajaxRequest, $data);
}

public function GetLogout($ajaxRequest){

  if(Session::fnLogoutUser()){
    echo
    '{"status" : "200",
    "message" : "You are logged out, see you"}';
    exit;
  }else{
    echo
    '{"status" : "401",
    "message" : "The system could not log you out, close browser for safety"}';
    exit;
  }

}

}

?>
