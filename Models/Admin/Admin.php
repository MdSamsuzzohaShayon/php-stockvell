<?php

namespace Models\Admin;

use Config\Database;

class Admin extends Database
{
    // public function __construct()
    // {
    // }

    public function setAdmin($admin_id, $name, $email, $phone, $password, $password2)
    {
        $this->admin_id = $admin_id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->password2 = $password2;
    }

    public function findAdminById($admin_id)
    {
        // echo $admin_id;
        $sql = "SELECT * FROM admins WHERE id=:admin_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array("admin_id" => $admin_id));
        $fabi_result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $fabi_result;
    }



    public function updateAdminProfile()
    {
        $update_elements = " name='$this->name', email='$this->email', phone='$this->phone'";
        if (!empty($this->password)) {
            if ($this->password !== $this->password2) {
                header('Location: /admin/?error=passwordnotmatch');
                exit();
            }
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password = $hashedPassword;
            $update_elements .= ", password='$this->password'";
        }

        $sql = "UPDATE admins SET $update_elements WHERE id=:admin_id";
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute(array("admin_id" => $this->admin_id))) {
            header("Location: /admin/?error=stmtfailed");
            exit();
        }
        header("Location: /admin/?error=none");
        exit();
    }
}
