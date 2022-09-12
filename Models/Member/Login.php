<?php

namespace Models\Member;
use Config\Database;
// use Models\Member\Member;

class Login extends Database
{

    public function __construct()
    {
        $this->phone = null;
        $this->email = null;
        $this->password = null;
    }

    public function setEmailPassword($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
    public function setPhonePassword($phone, $password)
    {
        $this->phone = $phone;
        $this->password = $password;
    }


    private function getMember($password, $email)
    {
        // select * from members where email='mdshayon0@gmail.com'
        $stmt = $this->connect()->prepare("SELECT * FROM members WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /login.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("Location: /login.php?error=usernotfound");
            exit();
        }


        $members = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $checkPassword = password_verify($password, $members[0]["password"]);

        if ($checkPassword  == false) {
            $stmt = null;
            header("Location: /login.php?error=incorrectpassword");
            exit();
        } elseif ($checkPassword == true) {
            // set cookie
            session_start();
            $_SESSION["member_id"] = $members[0]["id"];
            $_SESSION["member_role"] = $members[0]["role"];
            $_SESSION["member_username"] = $members[0]["firstname"] . " " . $members[0]["surname"];
            $_SESSION["member_email"] = $members[0]["email"];
            $stmt = null;
            header("Location: /dashboard.php");
        }

        $stmt = null;
    }



    public function loginMember()
    {
        if (empty($this->email) || empty($this->password)) {
            header("Location: /login.php?error=emptyinput");
            exit();
        }
        $this->getMember($this->password, $this->email);
    }



    private function getMemberByPhone($phone)
    {
        $stmt = $this->connect()->prepare("SELECT id, firstname, surname, role, phone, email, password  FROM members WHERE phone = :phone;");

        if (!$stmt->execute(array("phone" => $phone))) {
            $stmt = null;
            header("Location: /login/?error=stmtfailed");
            exit();
        }
        $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
        // var_dump($member_found);
        // exit();
        if (!$member_found) {
            $stmt = null;
            header("Location: /login/?error=usernotfound");
            exit();
        }
        return $member_found;
    }


    public function memberLoginViaPhone()
    {
        if (empty($this->phone) || empty($this->password)) {
            header("Location: /login.php?error=emptyinput");
            exit();
        }
        $fmbp_result = $this->getMemberByPhone($this->phone); // fmbp = find member by phone
        $checkPassword = password_verify($this->password, $fmbp_result->password);

        if ($checkPassword  == false) {
            header("Location: /login.php?error=incorrectpassword");
            exit();
        }
        session_start();
        $_SESSION["member_id"] = $fmbp_result->id;
        $_SESSION["member_role"] = $fmbp_result->role;
        $_SESSION["member_username"] = $fmbp_result->firstname . " " . $fmbp_result->surname;
        $_SESSION["member_email"] = $fmbp_result->email;
        header("Location: /dashboard.php");
    }
}
