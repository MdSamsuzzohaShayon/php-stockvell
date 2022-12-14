<?php
namespace Models\Admin;
use Config\Database;
/**
 * @all type of forms
 */


class AdminLogin extends Database
{
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
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
            header("Location: /admin");
            exit();
        }

        $stmt = null;
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
