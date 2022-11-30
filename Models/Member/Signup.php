<?php

namespace Models\Member;
// use Config\Database;
use Models\Member\Member;
use Utils\SendEmail;

class Signup extends Member
{
    public function __construct()
    {
        $this->ROOT = $_SERVER['DOCUMENT_ROOT'];
    }




    public function setMember($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $source, $city)
    {
        $this->firstname  = $firstname;
        $this->surname  = $surname;
        $this->email  = $email;
        $this->password  = $password;
        $this->password2  = $password2;
        $this->country  = $country;
        $this->phone  = $phone;
        $this->gender  = $gender;
        $this->profession  = $profession;
        $this->interest  = $interest;
        $this->source  = $source;
        $this->city = $city;
    }


    public function signupMember()
    {
        // echo $this->govt_id["size"];
        // exit();
        if (empty($this->firstname) || empty($this->email) || empty($this->password) || empty($this->password2) || empty($this->surname) || empty($this->country) || empty($this->phone) || empty($this->gender) || empty($this->profession) || empty($this->interest)  || empty($this->source)) {
            header("Location: /signup/?error=emptyinput");
            exit();
        }
        $user_firstname = str_replace(' ', '_', $this->firstname);
        // echo json_encode(array('firstname' => $user_firstname, "lastname" =>  $this->surname, 'condition' => preg_match("/^[a-zA-Z0-9_]*$/", $user_firstname)));
        // exit();
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $user_firstname) || strlen($this->firstname) <= 1) {
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





        // $unique_file_name = $this->uploadFileToServer($this->govt_id, $this->ROOT, "/signup/?");
        $this->saveMemberToDB($this->firstname, $this->surname, $this->email, $this->password, $this->country, $this->phone, $this->gender, $this->profession, $this->interest, $this->source, $this->city);
    }


    private function saveMemberToDB($firstname, $surname, $email, $password, $country, $phone, $gender, $profession, $interest, $source, $city)
    {
        try {
            //code...

            $stmt = $this->connect()->prepare('INSERT INTO members(firstname, surname, email, password,  country, phone, gender, profession, interest, source, role, city) VALUES (:firstname, :surname, :email, :password,  :country, :phone, :gender, :profession, :interest, :source, :role, :city);');

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
            $stmt->bindParam('source', $source, \PDO::PARAM_STR);
            $role = "GENERAL";
            $stmt->bindParam('role', $role, \PDO::PARAM_STR);
            if (!$stmt->execute()) {
                $stmt = null;
                header("Location: /signup/?error=stmtfailed");
                exit();
            }
            // Send message // send sms
            $send_email = new SendEmail();
            $html_msg = "
            <h3>Welcome to Stockvel</h3>
            <p>We would like to contratulate you to make a great decision of creating account in Stockvel and saving money with us. We assure your desire from us will be full filled.</p>
            <h3>Best of luck</h3>
                        ";
            $send_email->sendMessage($email, $html_msg, 'Account creation in Stockvel');

            $stmt = null;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
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
