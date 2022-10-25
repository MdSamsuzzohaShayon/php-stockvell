<?php

namespace Models\Admin;

use Config\Database;
use Models\Admin\Admin;

class AdminForms extends Admin
{
    // public function __construct()
    // {
    // }
    public function setAdmin($name, $email, $phone, $password, $password2)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->password2 = $password2;
    }


    public function updateProfile($admin_id)
    {
        if (empty($admin_id)) {
            header('Location: /admin/?error=emptyinput');
            exit();
        }
        // echo json_encode(array($this->name, $this->email, $this->phone, $this->password, $this->password2));
        // exit();
        if ($this->password) {
            if ($this->password !== $this->password2) {
                header("Location: /admin/?error=passwordnotmatch");
                exit();
            }
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password = $hashedPassword;
        }

        $input_list = $this->elementList();

        $cols = array();

        // Remove blank inputs and password2
        foreach ($input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }

        try {
            $this->updateAdminProfile($admin_id, $cols);
            header("Location: /admin/?error=none");
            exit();
            //code...
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
        }
    }
}
