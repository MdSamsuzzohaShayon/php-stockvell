<?php


/**
 * @database operations
 */
class Signup extends Database
{

  protected function setMember($firstname, $surname, $email, $password, $country, $phone, $gender, $profession, $interest, $govt_id, $source)
  {
    $stmt = $this->connect()->prepare('INSERT INTO members(firstname, surname, email, password,  country, phone, gender, profession, interest, govt_id, source, role) VALUES (:firstname, :surname, :email, :password,  :country, :phone, :gender, :profession, :interest, :govt_id, :source, :role);');

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam('firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam('surname', $surname, PDO::PARAM_STR);
    $stmt->bindParam('email', $email, PDO::PARAM_STR);
    $stmt->bindParam('password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam('country', $country, PDO::PARAM_STR);
    $stmt->bindParam('phone', $phone, PDO::PARAM_INT);
    $stmt->bindParam('gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam('profession', $profession, PDO::PARAM_STR);
    $stmt->bindParam('interest', $interest, PDO::PARAM_STR);
    $stmt->bindParam('govt_id', $govt_id, PDO::PARAM_STR);
    $stmt->bindParam('source', $source, PDO::PARAM_STR);
    $role = "GENERAL";
    $stmt->bindParam('role', $role, PDO::PARAM_STR);
    if (!$stmt->execute()) {
      $stmt = null;
      header("Location: ../signup.php?error=stmtfailed");
      exit();
    }
    $stmt = null;
  }


  protected function memberExist($email)
  {
    $stmt = $this->connect()->prepare("SELECT * FROM members WHERE email = :email");

    // select * from members where email='mdshayon0@gmail.com' or username='shayon';
    $stmt->bindParam('email', $email, PDO::PARAM_STR);
    if (!$stmt->execute()) {
      $stmt = null;
      header("Location: ../signup.php?error=stmtfailed");
      exit();
    }
    $resultCheck = null;
    if ($stmt->rowCount() > 0) {
      $resultCheck = true;
    } else {
      $resultCheck = false;
    }
    return $resultCheck;
  }
}





/**
 * @validate all inputs 
 */
class SignupController extends Signup
{
  private $firstname;
  private $surname;
  private $email;
  private $password;
  private $password2;
  private $country;
  private $phone;
  private $gender;
  private $profession;
  private $interest;
  private $govt_id;
  private $source;
  // private $role;

  public function __construct($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source)
  {
    $this->firstname = $firstname;
    $this->surname = $surname;
    $this->email = $email;
    $this->password = $password;
    $this->password2 = $password2;
    $this->country = $country;
    $this->phone = $phone;
    $this->gender = $gender;
    $this->profession = $profession;
    $this->interest = $interest;
    $this->govt_id = $govt_id;
    $this->source = $source;
  }

  public function signupMember()
  {
    if(empty($this->firstname) || empty($this->email) || empty($this->password) || empty($this->password2) || empty($this->surname) || empty($this->country) || empty($this->phone) || empty($this->gender) || empty($this->profession) || empty($this->interest) || empty($this->govt_id) || empty($this->source)){
      header("Location: ../signup.php?error=emptyinput");
      exit();
    }
    if (!preg_match("/^[a-zA-Z0-9]*$/", $this->username) || strlen($this->firstname) <= 1) {
      header("Location: ../signup.php?error=invalidusername");
      exit();
    }
    if(!preg_match ("/^[0-9]*$/", $this->phone)){
      header("Location: ../signup.php?error=invalidphone");
      exit();      
    }
    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      header("Location: ../signup.php?error=invalidemail");
      exit();
    }
    if ($this->password !== $this->password2) {
      header("Location: ../signup.php?error=passwordnotmatch");
      exit();
    }
    if ($this->memberExist($this->email) == true) {
      header("Location: ../signup.php?error=alreadyexist");
      exit();
    }


    $this->setMember($this->firstname, $this->surname, $this->email, $this->password, $this->country, $this->phone, $this->gender, $this->profession, $this->interest, $this->govt_id, $this->source);
  }
}
