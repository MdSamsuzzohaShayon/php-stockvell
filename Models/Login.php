<?php
namespace Models;
use Config\Database;
class Login extends Database
{

    public function __construct($email, $password)
    {
        $this->email = $email;
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

    private function getAdmin($email, $password)
    {
        // select * from members where email='mdshayon0@gmail.com'
        $stmt = $this->connect()->prepare("SELECT * FROM admins WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /admin.php?error=stmtfailed");
            exit();
        }


        $admin = $stmt->fetch(\PDO::FETCH_OBJ);
        if (!$admin) {
            $stmt = null;
            header("Location: /admin.php?error=usernotfound");
            exit();
        }
        $checkPassword = password_verify($password, $admin->password);

        if ($checkPassword  == false) {
            $stmt = null;
            header("Location: /admin.php?error=incorrectpassword");
            exit();
        } elseif ($checkPassword == true) {
            // set cookie
            session_start();
            $_SESSION["admin_id"] = $admin->id;
            $_SESSION["admin_role"] = $admin->role;
            $_SESSION["admin_username"] = $admin->name;
            $_SESSION["admin_email"] = $admin->email;
            $stmt = null;
            header("Location: /admin.php?error=none");
            exit();
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

    public function loginAdmin()
    {
        if (empty($this->email) || empty($this->password)) {
            header("Location: /admin.php?error=emptyinput");
            exit();
        }
        $this->getAdmin($this->email, $this->password);
    }
}
