<?php

namespace Models\Stockvell;

use Config\Database;
use Models\Stockvell\Stockvell;

class AdminStockvellForms extends Stockvell
{
    public function approveStockvellByAdmin($stockvell_id)
    {
        try {
            // Set withdraw member id and set withdraw date
            // Get single pack and get withdraw frequency from that
            // withdraw_frequency
            $single_stockvell = $this->getSingleStockvell($stockvell_id);
            $first_member_of_pack = $this->getFirstMemberOfAStockvellPack($stockvell_id);
            $first_widthdraw_member_id = null;
            $withdraw_frequency = 7;
            if ($first_member_of_pack->member_id) {
                $first_widthdraw_member_id = $first_member_of_pack->member_id;
            }
            if($single_stockvell->withdraw_frequency){
                $withdraw_frequency = $single_stockvell->withdraw_frequency;
            }
            $withdraw_date = date("Y-m-d");
            $offsetted_widthdraw_date =  date('Y-m-d', strtotime($withdraw_date . ' + '. $withdraw_frequency .' days'));
            // Update stockvell
            $input_list = array("status" => "APPROVED", "withdraw_member_id" => $first_widthdraw_member_id, 'withdraw_at' => $offsetted_widthdraw_date );
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
            // $this->addMemberToStockvell($stockvell_id, $leader_id);

            header('Location: /admin/?error=none');
            exit();
        } catch (\PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
        }
    }

    public function rejectStockvellPackByAdmin($stockvell_id, $redirect_url)
    {
        // Delete leader requests
        $this->deleteAllLeaderRequestOfAStockvell($stockvell_id);
        // delete members 
        $this->deleteAllMembersOfAStockvell($stockvell_id);
        // delete stockvells
        $this->deleteAllAStockvellPack($stockvell_id);
        header("Location: /$redirect_url?error=none");
        exit();
    }

    public function closeStockvellByAdmin($stockvell_id, $redirect_url)
    {
        /// Update element 
        $sql = "UPDATE stockvells SET status=:status WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);

        if (!$stmt->execute(array('stockvell_id' => $stockvell_id, 'status' => 'CLOSED'))) {
            header("Location: /$redirect_url?error=stmtfailed");
            exit();
        }


        // make many to many relationship 
        // $this->addMemberToStockvell($stockvell_id, $leader_id);

        header("Location: /$redirect_url?error=none");
        exit();
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
        exit();
    }
}
