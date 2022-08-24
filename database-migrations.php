<?php
// Delete this file in production
include ('./config/database.php');

class DatabaseMigrations extends Database{
  public function __construct(){
    // If there are no table with this name create one
    if(!$this->checkTableExist("members")){
      $sql_query = "CREATE TABLE members(
        id INT NOT NULL AUTO_INCREMENT,
        firstname VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        country VARCHAR(100) NOT NULL,
        phone VARCHAR(100) NOT NULL,
        gender VARCHAR(60) NOT NULL,
        profession VARCHAR(100) NOT NULL,
        interest TEXT,
        govt_id VARCHAR(100) NOT NULL,
        source TEXT,
        role VARCHAR(255) NOT NULL DEFAULT 'GENERAL',
        PRIMARY KEY (id),
        UNIQUE (email)
        );";
      $this->createTable($sql_query, "members");
    }
    if(!$this->checkTableExist("stockvells")){
      $sql_query = "CREATE TABLE stockvells(
        id INT  NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        agreement TEXT NOT NULL,
        goal VARCHAR(100) NOT NULL,
        category VARCHAR(100) NOT NULL,
        payment INT NOT NULL,
        payment_frequency INT NOT NULL,
        withdraw_member_id INT,
        withdraw_frequency INT NOT NULL,
        member_id INT,
        PRIMARY KEY(id),
        FOREIGN KEY(member_id) REFERENCES members(id),
        FOREIGN KEY(withdraw_member_id) REFERENCES members(id)
      );";
      $this->createTable($sql_query, "stockvells");
    }
  }
  private function checkTableExist($tablename){
    // $this->connect()->prepare('SELECT * FROM members');
    $stmt = $this->connect()->prepare('SHOW TABLES LIKE ?');
    $stmt->bindParam(1, $tablename);
    if(!$stmt->execute()){
      echo "git SQL error to check existing table";
      exit();
    }
    $hasTable = null;
    if($stmt->rowCount() > 0){
      echo "Table " . $tablename . " already exist  <br />";
      $hasTable = true;
    }else{
      $hasTable = false;
    }
    return $hasTable;
  }

  private function createTable($sql_query, $tablename){
    $stmt = $this->connect()->prepare($sql_query);
    if(!$stmt->execute()){
      echo "Got SQL error to create table";
      exit();
    }
    echo "created " . $tablename . " table successfully <br />";
  }

}

$dbMigrate =new DatabaseMigrations();


?>

<h2>Database migrations</h2>
