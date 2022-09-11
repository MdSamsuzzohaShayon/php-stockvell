<?php
namespace Config;

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

$dotenv = \Dotenv\Dotenv::createImmutable($ROOT);
$dotenv->safeLoad();


class Database{
  protected function connect(){
    try {
      $username = $_ENV["MYSQL_USER"];
      $password =  $_ENV["MYSQL_PASSWORD"];
      $db_name =  $_ENV["MYSQL_DATABASE"];
      $db_host= $_ENV["MYSQL_HOST"];
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
