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
        $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, source, city, role FROM members WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('email', $this->member_email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        // var_dump($result);
        return $result;
    }

    public function getAllMembers()
    {
        try {
            //code...
            $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, source, is_verified, role FROM members";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return [];
    }


    // Approved or not approved
    public function getSingleStockvell($stockvell_id)
    {
        try {
            $sql = "SELECT * FROM stockvells WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute(array("stockvell_id" => $stockvell_id));
            $ss_result = $stmt->fetch(\PDO::FETCH_OBJ); // ss = single stockvell
            return $ss_result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return null;
    }


    protected function getFirstMemberOfAStockvellPack($stockvell_id)
    {
        try {
            $sql = "SELECT sm.id, sm.member_id, sm.stockvell_id, m.firstname, m.email, m.country, m.city, m.phone, m.gender,  m.profession, m.interest, m.is_verified, m.source, m.role  
            FROM stockvell_to_member sm LEFT JOIN members m ON sm.member_id = m.id WHERE sm.stockvell_id=:stockvell_id LIMIT 1";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute(array("stockvell_id" => $stockvell_id));
            $first_result = $stmt->fetch(\PDO::FETCH_OBJ); // ss = single stockvell
            return $first_result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return null;
    }


    public function getNextMemberOfAStockvellPack($stockvell_id, $previous_member_id)
    {
        // echo $stockvell_id;
        try {
            $sql = "SELECT sm.id, sm.member_id, sm.stockvell_id, m.firstname, m.email, m.country, m.city, m.phone, m.gender,  m.profession, m.interest, m.is_verified, m.source, m.role  
            FROM stockvell_to_member sm LEFT JOIN members m ON sm.member_id = m.id WHERE sm.stockvell_id=:stockvell_id AND sm.status=:status AND sm.member_id > :previous_member_id LIMIT 1";
            $stmt = $this->connect()->prepare($sql);
            $new_status = "APPROVED";
            $stmt->execute(array("stockvell_id" => $stockvell_id, "previous_member_id" => $previous_member_id, "status"=> $new_status));
            $next_result = $stmt->fetch(\PDO::FETCH_OBJ); // ss = single stockvell
            // echo $next_result->member_id;
            // exit();
            return $next_result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return null;
    }





    public function isMemberBelongToStockvell($stockvell_id, $member_id)
    {
        try {
            //code...
            // echo $stockvell_id . $member_id;
            // exit();
            $new_status = "APPROVED";
            $mpSql = "SELECT * FROM stockvell_to_member WHERE stockvell_id=:stockvell_id AND member_id=:member_id AND status=:status";
            $mpStmt = $this->connect()->prepare($mpSql);
            // $tmid = 4;
            $mpStmt->bindParam("member_id", $member_id);
            $mpStmt->bindParam("stockvell_id", $stockvell_id);
            $mpStmt->bindParam("status", $new_status);
            $mpStmt->execute();
            $mp_result = $mpStmt->fetch(\PDO::FETCH_OBJ);
            if (empty($mp_result->id)) {
                return false;
            }
            return true;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return false;
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


    protected function deleteAllLeaderRequestOfAStockvell($stockvell_id)
    {
        try {
            //code...
            $sql = "DELETE FROM stockvell_lr_member WHERE stockvell_id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if ($stmt->execute(array('stockvell_id' => $stockvell_id))) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
            exit();
        }
        return false;
    }

    protected function deleteAllMembersOfAStockvell($stockvell_id)
    {
        try {
            //code...
            $sql = "DELETE FROM stockvell_to_member WHERE stockvell_id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if ($stmt->execute(array('stockvell_id' => $stockvell_id))) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
            exit();
        }
        return false;
    }

    protected function deleteAllAStockvellPack($stockvell_id)
    {
        try {
            //code...
            $sql = "DELETE FROM stockvells WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if ($stmt->execute(array('stockvell_id' => $stockvell_id))) {
                return true;
            } else {
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
