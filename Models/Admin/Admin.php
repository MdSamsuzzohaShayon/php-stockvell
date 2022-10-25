<?php

namespace Models\Admin;

use Config\Database;

class Admin extends Database
{
    // public function __construct()
    // {
    // }



    protected function elementList()
    {
        return array(
            "name" => $this->name,
            "email" => $this->email,
            "phone"  => $this->phone,
            "password"  => $this->password,
        );
    }





    public function updateAdminProfile($admin_id, $cols)
    {
        $updateElement = implode(', ', $cols);
        $sql = "UPDATE admins SET $updateElement WHERE id=$admin_id";
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
