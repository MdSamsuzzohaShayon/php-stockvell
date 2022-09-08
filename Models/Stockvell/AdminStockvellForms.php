<?php
namespace Models\Stockvell;
use Config\Database;

class AdminStockvellForms extends Database
{
    public function approveStockvellByAdmin($stockvell_id, $input_list, $leader_id)
    {
        // Update stockvell
        $cols = array();
        // Remove blank inputs and password2
        foreach ($input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }
        $updateElement = implode(', ', $cols);


        /// Update element 
        $sql = "UPDATE stockvells SET $updateElement WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('stockvell_id', $stockvell_id);

        if (!$stmt->execute()) {
            header('Location: /admin/?error=stmtfailed');
            exit();
        }


        // make many to many relationship 
        $this->addMemberToStockvell($stockvell_id, $leader_id);

        header('Location: /admin/?error=none');
    }

    public function addMemberToStockvell($stockvell_id, $member_id)
    {
        $sql = "INSERT INTO stockvell_to_member(stockvell_id, member_id) VALUES (:stockvell_id, :member_id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('stockvell_id', $stockvell_id);
        $stmt->bindParam('member_id', $member_id);
        if (!$stmt->execute()) {
            header('Location: /admin.php?error=stmtfailed');
            exit();
        }
        header('Location: /admin.php?error=none');
    }
}