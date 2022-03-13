<?php
/*
*
*/
class Database{

  private $_connection;
  private $_error;
  private static $_instance = null; //The single instance
  private $_db_host = DB_HOST;
  private $_db_username = DB_USERNAME;
  private $_db_password = DB_PASSWORD;
  private $_db_name = DB_NAME;

  // Constructor, create new PDO instance
  private function __construct(){
    //set DSN
    $dsn = 'mysql:host='.$this->_db_host. ';dbname='.$this->_db_name;
    $username = $this->_db_username;
    $password = $this->_db_password;

    //PDO error mode options
    $options = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    );

    try{
      $this->_connection  = new PDO($dsn, $this->_db_username, $this->_db_password);
      //echo 'Connected'; //check, should only echo once (singleton pattern)
    } catch (PDOException $e) {
      $this->error = $e->getMessage();
      echo $this->error;
    }
  }

  //get instance of database object (static function -> no class instanciation needed)
  public static function fnGetInstance(){
    if(!self::$_instance){ // If no instance exist
      self::$_instance = new self(); //instanciate one
    }
    return self::$_instance; //return the instance (which is either created or already exist)
  }

  //execute a named stored procedure that return data as array of objects (FETCH_OBJ)
  public function fnReadData($sStoredProcedure){
    $db = $this->_connection;
    $stmt = $db->query($sStoredProcedure);
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;

  }

  //execute stored procedure that write data
  public function fnWriteData($sStoredProcedure){
    $db = $this->_connection;
    $stmt = $db->query($sStoredProcedure);
    return $stmt;

  }


  public function fnCloseConnection(){}


}// end database class
?>
