<?php

class Images extends Controller{
  public function __construct(){
    if(!isset($_SESSION)) { session_start(); }

    //set model
    $this->imageModel = $this->model('Image');
  }

  public function index(){
    $this->view('pages/home', $ajaxRequest);
  }


/*
  Method that handles get request for photos
*/
  public function GetPhoto($ajaxRequest, $params){

      //redirect if user is not authenticated
    if(!Session::fnAuthenticateUser()){
      session_destroy();
      header('Location: '. URLROOT . 'users/login');
      exit;
    }

    //set default image output, if requested file can't be validated
    $errorFile = APPROOT.'/userupload/noimage.jpg';
    $errorHeader = header('Content-Type: image/jpg');


    //check params sent
    if(!isset($params) || sizeof($params) !== 2){

      echo $errorHeader;
      echo readfile($errorFile);
      exit;
    }
    //check format of params, params[0] = folder and params[1] = filename
    $folderPath = basename($params[0]); //remove any ../../ with basename
    $fileName = basename($params[1]);
    $filePath = APPROOT.'/userupload/'.$folderPath.'/'.$fileName;


    //foldername and filename is always a string pepresentation of a hexadecimal digit, check that
    //hex uses one byte per char set, 32 bytes = 64 chars, check that
    //A single place value in hexadecimal represents four bits of memory. That means two places represents eight bits, or one byte.
    if((!ctype_xdigit($folderPath) || !ctype_xdigit($fileName['filename'])) || (strlen($folderPath) !== 64 || strlen($fileName['filename']) !== 64)){
      echo $errorHeader;
      echo readfile($errorFile);
      exit;
    }

    //check if user has access to folders
    if(!in_array($folderPath, $_SESSION['album_folder_paths']) && !in_array($folderPath, $_SESSION['shared_album_folder_paths'])){
      echo $errorHeader;
      echo readfile($errorFile);
      exit;
    }

    //check if file exist and can be read
    if(!file_exists($filePath) || !is_readable($filePath)){
      echo $errorHeader;
      echo readfile($errorFile);
      exit;
    }

    //all checks have passed, now set headers

    //set header mime type
    switch ($fileName['extension']) {
      case 'jpg':
      $mime = 'image/jpeg';
      break;
      case 'jpeg':
      $mime = 'image/jpeg';
      break;
      case 'png':
      $mime = 'image/png';
      break;
      default:
      $mime = false;
    }

    if ($mime) {
      header('Content-type: '.$mime);
      header('Content-length: '.filesize($filePath));
    }
    header("Expires: Mon, 1 Jan 2099 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    echo readfile($filePath);
    exit;

  }//end getPhoto

/*
* GET Upload page method
*/
public function GetUpload($ajaxRequest){
     //redirect if user is not authenticated
  if(!Session::fnAuthenticateUser()){
    session_destroy();
    header('Location: '. URLROOT . 'users/login');
    exit;
  }

  $this->view('images/upload', $ajaxRequest);
}


/*
* POST Upload a new photo
*/
public function PostUpload($postBody){

    //redirect if user is not authenticated
  if(!Session::fnAuthenticateUser()){
    session_destroy();
    header('Location: '. URLROOT . 'users/login');
    exit;
  }

    //frontcontroller checks we have a post method
  if(isset($_FILES['files'])
    && !empty($_FILES['files'])
    && isset($postBody['MAX_FILE_SIZE'])){

      //check number of files in files array
    $totalFiles = count($_FILES['files']['name']);

    //if user uploads to many files, then exit
  if($totalFiles > 10){
    echo '{"message" : "Sorry, You can\'t upload more than 10 images at a time"}';
    exit;
  }


    //check what the browser uploads
  for($i=0; $i < $totalFiles; $i++){
        $fileName   = $_FILES['files']['name'][$i]; //name of client file name
        $fileSize   = $_FILES['files']['size'][$i]; // file size
        $fileTmp    = $_FILES['files']['tmp_name'][$i]; //temporary filename of the file stored on the server
        $fileType   = $_FILES['files']['type'][$i]; //mime type provided by the browser
        $fileError  = $_FILES['files']['error'][$i]; //php error array for uploaded files


        //if php error array is not having error code = 0, which is no errors
        if($fileError !== 0){

          switch ($fileError) {
            case 1:
            $message = $fileName. ' exceeds maximum filesize, max is 4mb.'; //exceeds max filesize in php global configuration
            case 2:
            $message = $fileName. ' exceeds maximum filesize, max is 4mb.'; //exceeds max filesize set in hidden form field
            break;
            case 4:
            $message = 'No files are selected.';
            break;
            default:
            $message = 'Sorry, there was a problem uploading ' .$fileName;
            break;
          }

          echo '{"status": "401", "message" : "'.$message.'"}';
          exit;

        }//end if not error code 0

        //custom checks on uploaded temporary file

        //check file size (if hidden form field should be spoofed) - not so relevant as global setting is also 4mb
        if(!Upload::fnCheckFileSize($fileTmp)){
          echo '{"status": "401", "message" : "'.$fileName.' is too big, max file size is 4mb"}';
          exit;
        }

        //check if files has the allowed mime type
        if(!Upload::fnCheckMimeType($fileTmp)){
          echo '{"status": "401", "message" : "'.$fileName.' is not an allowed file type, allowed is .jpg, .jpeg and .png"}';
          exit;
        }

        //check file extension
        if(!Upload::fnCheckFileExtension($fileName)){
          echo '{"status": "401", "message" : "'.$fileName.' is not an allowed file type, allowed is .jpg, .jpeg and .png"}';
          exit;
        }

        //check image if is image
        if(!Upload::fnCheckIsImage($fileTmp)){
          echo '{"status": "401", "message" : "'.$fileName.' is not an allowed file type, allowed is .jpg, .jpeg and .png"}';
          exit;
        }

      }// end for loop upload file

      //every file has passed the checks, now move from temp folder

      $folderNameBytes = openssl_random_pseudo_bytes(32);
      $folderName = bin2hex($folderNameBytes); //convert to hexidecimal
      $folder = mkdir(APPROOT . '/userupload/'.$folderName, 0644, true); //owner read/write, others only read permissions
      $folderPath = APPROOT. '/userupload/' . $folderName.'/'; //new album folder

      //decide name of album
      if(isset($postBody['album_name']) && !empty($postBody['album_name'])){
        $albumName = $postBody['album_name'];
      }else{
        $albumName = uniqid(); //random name if not set by user
      }

      //insert album into database
      $albumId = $this->imageModel->fnInsertAlbum(233, $albumName, $folderName);

      //returns the last inserted album id
      if($albumId){
        $albumId =  $albumId[0]->album_id;
      }else{
        echo '{"status": "401", "message" : "Something wen\'t wrong with your upload request"}';
        exit;
      }


      //rename files and move from temp folder to userupload folder
      for($i=0; $i < $totalFiles; $i++){
        $fileName   = $_FILES['files']['name'][$i]; //name of client file name
        $fileTmp    = $_FILES['files']['tmp_name'][$i]; //temporary filename of the file stored on the server

        //generate random filename
        $ranFileNameBytes = openssl_random_pseudo_bytes(32);
        $ranFileName = bin2hex($ranFileNameBytes);

        //get last item in the array (the file extension)
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = $ranFileName .'.'. strtolower($fileExt);

        //basename makes sure we only get the filename, and no path
        $filePath = $folderPath.basename($fileName);

        //if move succeeds, change file permissons to 644 -> (http://permissions-calculator.org/decode/0644/)
        if(move_uploaded_file($fileTmp, $filePath)){
          chmod($filePath, 0644);

          //insert photo to database
          if(!$this->imageModel->fnInsertPhoto($albumId, $fileName)){
            echo '{"status": "500", "message" : "'.$_FILES['files']['name'][$i].' failed uploading"}';
            continue;
          }

        }else{
          echo '{"status": "500", "message" : "'.$_FILES['files']['name'][$i].' failed uploading"}';
          continue;
        }

      }
      echo '{"status": "200", "message" : "Everything wen\'t okay with your album upload"}';

      //update session values
      array_push($_SESSION['album_folder_paths'], $folderName);
      $_SESSION['user']->albums++;
      exit;

    }else{ //not post or files array is empty (or MAX_FILE_SIZE removed/tampered)
      echo '{"status" : "401", "message" : "Something went wrong with your request, did you add files to upload?"}';
      exit;
    }

  } // end PostUpload method



  public function PostComment($postBody){

    //check in database if user is owner, or if user has rights to post comment, if he is not the owner
    $userId = $this->imageModel->fnIsUserPhotoOwner($postBody['photo_id']);
    $userShared = $this->imageModel->fnCanUserComment($postBody['photo_id']);

    //one has to be true
    if(($userId && $userId[0]->user_id == $_SESSION['user']->user_id) ||
     ($userShared && $userShared[0]->user_id == $_SESSION['user']->user_id &&
      $userShared[0]->can_comment == 1)){

      //encrypt comment
      $arrayCipher = Encrypt::fnEncrypt($postBody['comment']);

      //insert comment (photo_id, user_id, comment, init vector)
    if(!$this->imageModel->fnInsertComment($postBody['photo_id'], $_SESSION['user']->user_id, $arrayCipher[0], $arrayCipher[1])){

      echo '{"status": "401", "message" : "Could not post comment"}';
      exit;
    }else{
      echo '{"status":"200", "message":"Your comment was posted"}';
      exit;
    }

  }
  echo '{"status": "401", "message" : "Could not post comment"}';
  exit;

  } //end post comment

} // Images controller class end

?>
