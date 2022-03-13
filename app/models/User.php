<?php
class User {

  private $_db;

  public function __construct(){
    $this->_db = Database::fngetInstance();
  }

  public function fnLoginUser($userEmail, $userPassword){
    $result = $this->_db->fnReadData("CALL loginUser('$userEmail')");
    if(Hash::fnVerifyPassword("$userPassword", $result[0]->pass)){
      return $result;
    }else{
      return false;
    }
  }

  public function fnUpdateLoginAttempts($userEmail){
    $this->_db->fnWriteData("CALL updateLoginAttempts('$userEmail')");
    return true;
  }
  public function fnIsUserBanned($userEmail){
    $result = $this->_db->fnReadData("CALL isUserBanned('$userEmail')");
    return $result;
  }

  public function fnClearLoginAttempts($userEmail){
    $this->_db->fnWriteData("CALL clearLoginAttempts('$userEmail')");
    return true;
  }

  public function fnRegisterUser($userFirstName, $userLastName, $userEmail, $userPassword){
    return $this->_db->fnWriteData("CALL registerUser('$userFirstName', '$userLastName', '$userEmail', '$userPassword')");
  }

  public function fnGetUsersFolders($userId){
    return $this->_db->fnReadData("CALL getAlbumFolderPaths('$userId')");
  }

  public function fnGetUsersSharedFolders($userId){
    return $this->_db->fnReadData("CALL getSharedAlbumFolderPaths('$userId')");
  }



/*
  Get a users albums
*/
  public function fnGetUserAlbums($userId){
    return $this->_db->fnReadData("CALL getUserAlbums('$userId')");
  }

/*
  Get a albums shared to user
*/
  public function fnGetUserSharedAlbums($userId){
    return $this->_db->fnReadData("CALL getUserSharedAlbums('$userId')");
  }

/*
  Get a users albums photos
*/
  public function fnGetAlbumPhotos($userId, $albumId){
    return $this->_db->fnReadData("CALL getAlbumPhotos('$userId', '$albumId')");
  }

/*
  Get a users shared albums photos
*/
  public function fnGetSharedAlbumPhotos($userId, $albumId){
    return $this->_db->fnReadData("CALL getUserSharedAlbumPhotos('$userId', '$albumId')");
  }

/*
  Get a users single photo site
*/

  public function fnFetchPhoto($photoId){
    return $this->_db->fnReadData("CALL getPhoto('$photoId')");
  }

/*
Blacklist IP address
*/
public function fnBlacklistIp($ip){
  return $this->_db->fnWriteData("CALL blacklistIp('$ip')");
}

/*
Check if user is blaclisted
*/
public function fnIsUserBlackList($ip){
  return $this->_db->fnReadData("CALL isUserBlacklisted('$ip')");
}











}

?>

