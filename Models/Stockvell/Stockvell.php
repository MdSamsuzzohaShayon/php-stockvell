<?php

namespace Models\Stockvell;

use Config\Database;

class Stockvell extends Database
{
    // private $member_email;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setMember($member_email, $leader_id)
    {
        $this->member_email = $member_email;
        $this->leader_id = $leader_id;
    }


    public function getCurrentMember()
    {
        $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, city, role FROM members WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('email', $this->member_email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        // var_dump($result);
        return $result;
    }

    public function getAllMembers()
    {
        $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, is_verified, role FROM members";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }


    // Approved or not approved
    protected function getSingleStockvell($stockvell_id){
        $sql = "SELECT * FROM stockvells WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array("stockvell_id" => $stockvell_id));
        $ss_result = $stmt->fetch(\PDO::FETCH_OBJ); // ss = single stockvell
        return $ss_result; 
    }

   



    public function isMemberBelongToStockvell($stockvell_id, $member_id)
    {
        // echo $stockvell_id . $member_id;
        // exit();
        $mpSql = "SELECT * FROM stockvell_to_member WHERE stockvell_id=:stockvell_id AND member_id=:member_id";
        $mpStmt = $this->connect()->prepare($mpSql);
        // $tmid = 4;
        $mpStmt->bindParam("member_id", $member_id);
        $mpStmt->bindParam("stockvell_id", $stockvell_id);
        $mpStmt->execute();
        $mp_result = $mpStmt->fetch(\PDO::FETCH_OBJ);
        if (empty($mp_result->id)) {
            return false;
        }
        return true;
    }





    // PSC = pending stockvell pack // relationship query
    public function getPSC($status)
    {
        // All stockvell of a member
        $sql = "SELECT * FROM stockvells WHERE status=:status";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }


    protected function deleteAllLeaderRequestOfAStockvell($stockvell_id){
        try {
            //code...
            $sql = "DELETE FROM stockvell_lr_member WHERE stockvell_id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if($stmt->execute(array('stockvell_id'=>$stockvell_id))){
                return true;
            }else{
                return false;
            }
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
            exit();
        }
        return false;
    }

    protected function deleteAllMembersOfAStockvell($stockvell_id){
        try {
            //code...
            $sql = "DELETE FROM stockvell_to_member WHERE stockvell_id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if($stmt->execute(array('stockvell_id'=>$stockvell_id))){
                return true;
            }else{
                return false;
            }
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
            exit();
        }
        return false;
    }

    protected function deleteAllAStockvellPack($stockvell_id){
        try {
            //code...
            $sql = "DELETE FROM stockvells WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if($stmt->execute(array('stockvell_id'=>$stockvell_id))){
                return true;
            }else{
                return false;
            }
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
            exit();
        }
        return false;
    }

    
}
