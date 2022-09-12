<?php 
namespace Models\Member;
// use Config\Database;
use Models\Member\Member;
class Signup extends Member
{
    public function __construct()
    {
        parent::__construct();
    }


    // public function delete_prev_file_from_server($prev_name, $ROOT)
    // {
    //     $target_dir = $ROOT . "/uploads/";
    //     if (file_exists($target_dir . $prev_name)) {
    //         unlink($target_dir . $prev_name);
    //     }
    // }

    /*
    public function uploadFileToServer($uploadedFile, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $uploadedFile["name"]);
        $target_file = $target_dir . basename($unique_file_name . $uploadedFile["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // 1kb = 1000, 1 mb = 1000 kb
        if ($uploadedFile['size'] > (1000 * 1000 * 2)) {
            header("Location: /signup/?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: /signup/?error=invalidfile");
            exit();
        }
        // Upload file
        move_uploaded_file($uploadedFile["tmp_name"], $target_dir . $unique_file_name);
        return $unique_file_name;
    }
    */


    public function signupMember()
    {
        // echo $this->govt_id["size"];
        // exit();
        if (empty($this->firstname) || empty($this->email) || empty($this->password) || empty($this->password2) || empty($this->surname) || empty($this->country) || empty($this->phone) || empty($this->gender) || empty($this->profession) || empty($this->interest)  || empty($this->source)) {
            header("Location: /signup/?error=emptyinput");
            exit();
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->username) || strlen($this->firstname) <= 1) {
            header("Location: /signup/?error=invalidusername");
            exit();
        }
        // if (!preg_match("/^[0-9]*$/", $this->phone)) {
        //     header("Location: /signup/?error=invalidphone");
        //     exit();
        // }
        if (strlen($this->phone) < 6) {
            header("Location: /signup/?error=invalidphone");
            exit();
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /signup/?error=invalidemail");
            exit();
        }
        if ($this->password !== $this->password2) {
            header("Location: /signup/?error=passwordnotmatch");
            exit();
        }
        if ($this->findMemberByEmail($this->email, "signup/")) {
            header("Location: /signup/?error=alreadyexist");
            exit();
        }
        if ($this->findMemberByPhone($this->phone, "signup/")) {
            header("Location: /signup/?error=alreadyexistphone");
            exit();
        }




        /*
        // File inputs
        $target_dir = $this->ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $this->govt_id["name"]);
        $target_file = $target_dir . basename($unique_file_name . $this->govt_id["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // 1kb = 1000, 1 mb = 1000 kb
        if ($this->govt_id['size'] > (1000 * 1000 * 2)) {
            header("Location: /signup/?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: /signup/?error=invalidfile");
            exit();
        }
        // if (file_exists($target_dir . $unique_file_name)) {
        //     header("Location: /signup/?error=invalidfile");
        //     exit();
        // }


        // Upload file
        move_uploaded_file($this->govt_id["tmp_name"], $target_dir . $unique_file_name);
        // move_uploaded_file($this->govt_id["tmp_name"], "uploads/" . $this->govt_id["name"]);
        */

        $unique_file_name = $this->uploadFileToServer($this->govt_id, $this->ROOT, true);
        $this->saveMemberToDB($this->firstname, $this->surname, $this->email, $this->password, $this->country, $this->phone, $this->gender, $this->profession, $this->interest, $unique_file_name, $this->source, $this->city);
    }


    private function saveMemberToDB($firstname, $surname, $email, $password, $country, $phone, $gender, $profession, $interest, $govt_id, $source, $city)
    {

        $stmt = $this->connect()->prepare('INSERT INTO members(firstname, surname, email, password,  country, phone, gender, profession, interest, govt_id, source, role, city) VALUES (:firstname, :surname, :email, :password,  :country, :phone, :gender, :profession, :interest, :govt_id, :source, :role, :city);');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam('firstname', $firstname, \PDO::PARAM_STR);
        $stmt->bindParam('surname', $surname, \PDO::PARAM_STR);
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        $stmt->bindParam('password', $hashedPassword, \PDO::PARAM_STR);
        $stmt->bindParam('country', $country, \PDO::PARAM_STR);
        $stmt->bindParam('city', $city, \PDO::PARAM_STR);
        $stmt->bindParam('phone', $phone, \PDO::PARAM_STR);
        $stmt->bindParam('gender', $gender, \PDO::PARAM_STR);
        $stmt->bindParam('profession', $profession, \PDO::PARAM_STR);
        $stmt->bindParam('interest', $interest, \PDO::PARAM_STR);
        $stmt->bindParam('govt_id', $govt_id, \PDO::PARAM_STR);
        $stmt->bindParam('source', $source, \PDO::PARAM_STR);
        $role = "GENERAL";
        $stmt->bindParam('role', $role, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: /signup/?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }


    private function memberExist($email)
    {
        $stmt = $this->connect()->prepare("SELECT * FROM members WHERE email = :email");

        // select * from members where email='mdshayon0@gmail.com' or username='shayon';
        $stmt->bindParam('email', $email, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: /signup/?error=stmtfailed");
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