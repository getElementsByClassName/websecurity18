<?php

Class Image {
  private $_db;

  public function __construct(){
    $this->_db = Database::fngetInstance();
  }

  //insert album
  public function fnInsertAlbum($UserId, $AlbumName, $FilePath){
    return $this->_db->fnReadData("CALL insertAlbum($UserId, '$AlbumName', '$FilePath');");
  }

  //insert image method
  public function fnInsertPhoto($AlbumId, $FilePath){
    return $this->_db->fnWriteData("CALL insertPhoto($AlbumId, '$FilePath');");
  }

  //insert comment method
  public function fnInsertComment($photoId, $userId, $comment, $iv){
    return $this->_db->fnWriteData("CALL insertComment('$photoId', $userId, '$comment', '$iv');");
  }

  //check if user is owner of photo, if he tries to comment
  public function fnIsUserPhotoOwner($photoId){
    return $this->_db->fnReadData("CALL isUserPhotoOwner('$photoId');");
  }

  //check if photo is in a shared album where user is allowed to comment
  public function fnCanUserComment($photoId){
    return $this->_db->fnReadData("CALL canUserComment('$photoId');");
  }

}


?>
