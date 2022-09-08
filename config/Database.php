<?php
namespace Config;

class Database{
  protected function connect(){
    try {
      $username = "shayon";
      $password = "Test1234";
      $db_name = "stockvell_finance_db";
      $db_host= "localhost";
      $conn = new \PDO("mysql:host=$db_host;dbname=$db_name", $username, $password);
      // echo $conn;
      // exit();
      return $conn;
    } catch (\PDOException $e) {
      echo "Error: " . $e->getMessage(). "<br />";
      die();
    }

  }
}
 ?>
