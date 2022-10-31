<?php
// Delete this file in production
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


class Database
{
  protected function connect()
  {
    try {
      $username = $_ENV["MYSQL_USER"];
      $password =  $_ENV["MYSQL_PASSWORD"];
      $db_name =  $_ENV["MYSQL_DATABASE"];
      $db_host = $_ENV["MYSQL_HOST"];
      $conn = new \PDO("mysql:host=$db_host;dbname=$db_name", $username, $password);
      // echo $conn;
      // exit();
      return $conn;
    } catch (\PDOException $e) {
      echo "Error: " . $e->getMessage() . "<br />";
      die();
    }
  }
}


class DatabaseMigrations extends Database
{
  public function __construct()
  {

    /**
     * @delete tables
     */
    /*
    $this->deleteTable("admins");
    $this->deleteTable("stockvell_to_member");
    $this->deleteTable("stockvell_lr_member");
    $this->deleteTable("stockvells");
    $this->deleteTable("members");
    */



    /**
     * @create table if there is none
     */


    // If there are no table with this name create one

    if (!$this->checkTableExist("members")) {
      $sql_query = "CREATE TABLE members(
            id INT NOT NULL AUTO_INCREMENT,
            firstname VARCHAR(100) NOT NULL,
            surname VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            recovery_code VARCHAR(100),
            country VARCHAR(100) NOT NULL,
            city VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            gender VARCHAR(60) NOT NULL,
            profession VARCHAR(100) NOT NULL,
            interest TEXT,
            govt_id VARCHAR(100) NOT NULL,
            is_verified BOOLEAN NOT NULL DEFAULT false,
            source TEXT,
            role VARCHAR(255) NOT NULL DEFAULT 'GENERAL',
            PRIMARY KEY (id),
            UNIQUE (email),
            UNIQUE (phone)
            );";
      $this->createTable($sql_query, "members");
    }


    if (!$this->checkTableExist("stockvells")) {
      $sql_query = "CREATE TABLE stockvells(
        id INT  NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        agreement TEXT NOT NULL,
        goal VARCHAR(100) NOT NULL,
        category VARCHAR(100) NOT NULL,
        status VARCHAR(100) NOT NULL DEFAULT 'PENDING',
        payment INT NOT NULL,
        currency VARCHAR(60) NOT NULL,
        payment_frequency INT NOT NULL,
        withdraw_frequency INT NOT NULL,
        withdraw_member_id INT,
        withdraw_at DATE,
        leader_id INT,
        link VARCHAR(100),
        max_member INT NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(leader_id) REFERENCES members(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(withdraw_member_id) REFERENCES members(id) ON DELETE CASCADE ON UPDATE CASCADE
        );";
      $this->createTable($sql_query, "stockvells");
    }
    // Junction table for many to many relationship with memeber and stockvells
    if (!$this->checkTableExist("stockvell_to_member")) {
      $sql_query = "CREATE TABLE stockvell_to_member(
        id BIGINT NOT NULL AUTO_INCREMENT, 
        stockvell_id INT  NOT NULL,
        member_id INT  NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(stockvell_id) REFERENCES stockvells(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(member_id) REFERENCES members(id)  ON DELETE CASCADE ON UPDATE CASCADE
        );";
      $this->createTable($sql_query, "stockvell_to_member");
    }


    if (!$this->checkTableExist("stockvell_lr_member")) { // lr = leader request
      $sql_query = "CREATE TABLE stockvell_lr_member (
        id INT NOT NULL AUTO_INCREMENT,
        stockvell_id INT NOT NULL,
        member_id INT NOT NULL,
        govt_id_proof VARCHAR(150) NOT NULL,
        address_proof VARCHAR(150) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY(stockvell_id) REFERENCES stockvells(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(member_id) REFERENCES members(id) ON DELETE CASCADE ON UPDATE CASCADE
      )";
      $this->createTable($sql_query, "stockvell_lr_member");
    }


    if (!$this->checkTableExist("admins")) {
      $sql_query = "CREATE TABLE admins(
        id INT NOT NULL AUTO_INCREMENT, 
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(255) NOT NULL DEFAULT 'SUPER',
        PRIMARY KEY(id)
        );";
      $this->createTable($sql_query, "admins");
      $this->addToTheAdminsTable("admins", "stockvell_admin", "stockvelladmin@gmail.com", "+880_1785208590", "Test1234");
    }






    // Update or modify specific table
    /*
    // Not required in production/
    $modify_sql = "ALTER TABLE members ADD COLUMN city VARCHAR(255) NOT NULL";
    $modify_sql = "ALTER TABLE members ADD COLUMN recovery_code VARCHAR(100)";
    $modify_sql = "ALTER TABLE members MODIFY phone VARCHAR(150) UNIQUE NOT NULL";
    $modify_sql = "UPDATE admins SET phone='+880_1785208590' WHERE id='1'";
    $modify_sql = "ALTER TABLE stockvells ADD COLUMN description TEXT NOT NULL";
    */

    /*
    $modify_members_sql = "ALTER TABLE stockvells ADD COLUMN currency VARCHAR(60) NOT NULL";
    $this->specificTableModifications($modify_members_sql, "Added currency to members");

    $modify_stockvell_sql = "ALTER TABLE stockvells ADD COLUMN max_member INT NOT NULL";
    $this->specificTableModifications($modify_stockvell_sql, "Added max_member to members");

    $modify_stockvell_sql = "ALTER TABLE stockvells ADD COLUMN link VARCHAR(100)";
    $this->specificTableModifications($modify_stockvell_sql, "Added link to members");


    $modify_stockvell_withdraw_sql = "ALTER TABLE stockvells ADD COLUMN withdraw_member_id INTEGER FOREIGN KEY(withdraw_member_id) REFERENCES members(id)  ON DELETE CASCADE ON UPDATE CASCADE";
    $this->specificTableModifications($modify_stockvell_withdraw_sql, "Added withdraw_member_id to members");
    */

    // $modify_members_sql = "ALTER TABLE stockvells ADD COLUMN withdraw_at DATE";
    // $this->specificTableModifications($modify_members_sql, "Added withdraw_at to stockvells");
  }










  private function checkTableExist($tablename)
  {
    try {
      $sql = "SHOW TABLES LIKE '$tablename'";
      // $this->connect()->prepare('SELECT * FROM members');
      $stmt = $this->connect()->prepare($sql);
      // $stmt->bindParam(1, $tablename);
      $stmt->execute();

      $hasTable = null;
      if ($stmt->rowCount() > 0) {
        echo "Table " . $tablename . " already exist  <br />";
        $hasTable = true;
      } else {
        $hasTable = false;
      }
      return $hasTable;
    } catch (\PDOException $e) {
      //throw $th;
      echo $e->getMessage();
      exit();
    }
    return false;
  }

  private function createTable($sql_query, $tablename)
  {
    try {
      //code...
      $stmt = $this->connect()->prepare($sql_query);
      $stmt->execute();

      echo "created " . $tablename . " table successfully <br />";
    } catch (\PDOException $e) {
      //throw $th;
      echo "error to create $tablename </br>";
      echo $e->getMessage();
      exit();
    }
  }

  private function deleteTable($tablename)
  {
    $before_delete_stmt = $this->connect()->prepare("SET FOREIGN_KEY_CHECKS=0;"); // Not working
    $before_delete_stmt->execute();
    try {
      $sql = "DROP TABLE " . $tablename;
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      //code...
    } catch (\PDOException $e) {
      //throw $th;
      echo $e->getMessage();
      exit();
    }
    $after_delete_stmt = $this->connect()->prepare("SET FOREIGN_KEY_CHECKS=1;"); // Not working
    $after_delete_stmt->execute();
    echo "Deleted " . $tablename . " table successfully <br />";
  }

  private function addToTheAdminsTable($tablename, $name, $email, $phone, $password)
  {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = "SUPER";
    $stmt = $this->connect()->prepare("INSERT INTO $tablename(name, email, phone, password, role) VALUES (?, ?, ?, ?, ?);");
    if (!$stmt->execute(array($name, $email, $phone, $hashedPassword, $role))) {
      echo "Got SQL error to create table";
      exit();
    }
    echo "Added record to " . $tablename . " table successfully <br />";
  }


  private function specificTableModifications($sql, $msg)
  {
    // ALTER TABLE members ADD COLUMN is_verified BOOLEAN NOT NULL DEFAULT false;
    try {
      //code...
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      echo "$msg.<br />";
    } catch (\PDOException $err) {
      //throw $th;
      echo $err->getMessage();
    }
  }
}

$dbMigrate = new DatabaseMigrations();


?>

<h2 style="color:green;font-size:4rem;">All database migrations successfully</h2>