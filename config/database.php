<?php

// MYSQL_ROOT_PASSWORD=Test1234
// MYSQL_DATABASE=stockvell_finance_db
// MYSQL_USER=shayon
// MYSQL_PASSWORD=Test1234
// # Got host from docker file
// MYSQL_HOST=mysql_db
// MYSQL_PORT=3306

class Database{
  protected function connect(){
    try {
      $username = "shayon";
      $password = "Test1234";
      $db_name = "stockvell_finance_db";
      $conn = new PDO("mysql:host=mysql_db;dbname=stockvell_finance_db", $username, $password);
      // echo $conn;
      // exit();
      return $conn;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage(). "<br />";
      die();
    }

  }
}
 ?>
