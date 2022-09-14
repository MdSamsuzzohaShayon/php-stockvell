<?php

namespace Models\Admin;

use Config\Database;

class FetchAdmin extends Database
{
    public function findAdminById($admin_id)
    {
        // echo $admin_id;
        $sql = "SELECT * FROM admins WHERE id=:admin_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array("admin_id" => $admin_id));
        $fabi_result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $fabi_result;
    }
}
