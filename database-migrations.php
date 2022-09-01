<?php
// Delete this file in production
include('./config/database.php');

class DatabaseMigrations extends Database
{
  public function __construct()
  {
    // $this->deleteTable("stockvells");
    // $this->deleteTable("stockvell_to_member");
    // If there are no table with this name create one
    if (!$this->checkTableExist("members")) {
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
    if (!$this->checkTableExist("stockvells")) {
      $sql_query = "CREATE TABLE stockvells(
        id INT  NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        agreement TEXT NOT NULL,
        goal VARCHAR(100) NOT NULL,
        category VARCHAR(100) NOT NULL,
        status VARCHAR(100) NOT NULL DEFAULT 'PENDING',
        payment INT NOT NULL,
        payment_frequency INT NOT NULL,
        withdraw_frequency INT NOT NULL,
        withdraw_member_id INT,
        leader_id INT,
        PRIMARY KEY(id),
        FOREIGN KEY(leader_id) REFERENCES members(id),
        FOREIGN KEY(withdraw_member_id) REFERENCES members(id)
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
    if (!$this->checkTableExist("admins")) {
      $sql_query = "CREATE TABLE admins(
        id INT NOT NULL AUTO_INCREMENT, 
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(255) NOT NULL DEFAULT 'SUPER',
        PRIMARY KEY(id)
        );";
      $this->createTable($sql_query, "admins");
    }
    $this->addToTheAdminsTable("admins", "stockvell_admin", "stockvellexample@gmail.com", "1234567", "Test1234");

    // Update or modify specific table
    $modify_sql = "ALTER TABLE members ADD COLUMN is_verified BOOLEAN NOT NULL DEFAULT false";
    $this->specificTableModifications($modify_sql, "Added another column to table");
  }









  
  private function checkTableExist($tablename)
  {
    // $this->connect()->prepare('SELECT * FROM members');
    $stmt = $this->connect()->prepare('SHOW TABLES LIKE ?');
    $stmt->bindParam(1, $tablename);
    if (!$stmt->execute()) {
      echo "git SQL error to check existing table";
      exit();
    }
    $hasTable = null;
    if ($stmt->rowCount() > 0) {
      echo "Table " . $tablename . " already exist  <br />";
      $hasTable = true;
    } else {
      $hasTable = false;
    }
    return $hasTable;
  }

  private function createTable($sql_query, $tablename)
  {
    $stmt = $this->connect()->prepare($sql_query);
    if (!$stmt->execute()) {
      echo "Got SQL error to create table";
      exit();
    }
    echo "created " . $tablename . " table successfully <br />";
  }

  private function deleteTable($tablename)
  {
    $before_delete_stmt = $this->connect()->prepare("SET FOREIGN_KEY_CHECKS=0;"); // Not working
    $before_delete_stmt->execute();
    $stmt = $this->connect()->prepare("DROP TABLE $tablename");
    if (!$stmt->execute()) {
      echo "Got SQL error to create table";
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


  private function specificTableModifications($sql, $msg){
    // ALTER TABLE members ADD COLUMN is_verified BOOLEAN NOT NULL DEFAULT false;
    
    $stmt = $this->connect()->prepare($sql);
    if (!$stmt->execute()) {
      echo "Got SQL error to modify table";
      exit();
    }
    echo "$msg.<br />";
  }
}

$dbMigrate = new DatabaseMigrations();


?>

<h2>Database migrations</h2>