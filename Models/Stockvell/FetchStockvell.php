<?php

namespace Models\Stockvell;

use Config\Database;
use Models\Stockvell\Stockvell;

class FetchStockvell extends Stockvell
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSingleStockvellPack($stockvell_id)
    {
        return $this->getSingleStockvell($stockvell_id);
    }


    private function makeStockvellArray($single_stockvell, $total_members, $members)
    {
    }



    public function memberWhoRequestToBeLeader($member_id, $stockvell_id)
    {
        $sql = "SELECT * FROM stockvell_lr_member WHERE stockvell_id=:stockvell_id AND member_id=:member_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array("stockvell_id" => $stockvell_id, "member_id" => $member_id));
        $result = $stmt->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    public function allMembersWhoRequestToBeLeader($stockvell_id)
    {
        $sql = "SELECT sm.id, sm.member_id, sm.stockvell_id, sm.govt_id_proof, sm.address_proof, s.name, s.goal, s.category, s.leader_id, m.firstname, m.surname, m.email, m.phone FROM stockvell_lr_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id LEFT JOIN members m ON sm.member_id=m.id WHERE stockvell_id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array("stockvell_id" => $stockvell_id));
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }


    public function getASingleApprovedStockvell($stockvell_id)
    {
        $stockvell_result = $this->getASingleApprovedStockvellWithMembers($stockvell_id);
        if (empty($stockvell_result["id"])) {
            $stockvell_result = $this->getASingleApprovedStockvellWithoutMembers($stockvell_id);
        }
        return $stockvell_result;
    }

    public function getASingleApprovedStockvellWithoutMembers($stockvell_id)
    {
        try {
            $stockvell_sql = "SELECT * FROM stockvells WHERE id=:stockvell_id";
            $stockvell_stmt = $this->connect()->prepare($stockvell_sql);
            $stockvell_stmt->bindParam('stockvell_id', $stockvell_id);
            $stockvell_stmt->execute();
            $stockvell_result = $stockvell_stmt->fetch(\PDO::FETCH_OBJ);
            $new_result = [
                'id' => $stockvell_result->id,
                'name' => $stockvell_result->name,
                'description' => $stockvell_result->description,
                'link' => $stockvell_result->link,
                'agreement' => $stockvell_result->agreement,
                'status' => $stockvell_result->status,
                'goal' => $stockvell_result->goal,
                'leader_id' => $stockvell_result->leader_id,
                'withdraw_member_id' => $stockvell_result->withdraw_member_id,
                'category' => $stockvell_result->category,
                'max_member' => $stockvell_result->max_member,
                'payment' => $stockvell_result->payment,
                'currency' => $stockvell_result->currency,
                'payment_frequency' => $stockvell_result->payment_frequency,
                'withdraw_frequency' => $stockvell_result->withdraw_frequency,
                'withdraw_at' => $stockvell_result->withdraw_at,
                'total_members' => 0,
                'members' => [],
            ];
            return $new_result;
        } catch (\PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
        }
        return null;
    }

    // getAllApprovedStockvellOfAMember - reverse left join and group by of this function
    public function getASingleApprovedStockvellWithMembers($stockvell_id)
    {
        try {
            // $stockvell_sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm 
            // LEFT JOIN stockvells s ON sm.stockvell_id=s.id 
            // WHERE sm.stockvell_id=:stockvell_id
            // GROUP BY sm.stockvell_id";
            $stockvell_sql = "SELECT sm.id, sm.stockvell_id, s.link, s.agreement, s.description, s.goal, s.leader_id, s.withdraw_member_id, s.withdraw_at, s.category, s.max_member, s.payment, s.currency, s.payment_frequency, s.withdraw_frequency, s.name, s.status,
        sm.member_id, m.firstname, m.surname, m.profession, m.country
        FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id LEFT JOIN members m ON sm.member_id=m.id WHERE s.id=:stockvell_id;";
            $stockvell_stmt = $this->connect()->prepare($stockvell_sql);
            $stockvell_stmt->bindParam('stockvell_id', $stockvell_id);
            $stockvell_stmt->execute();
            $stockvell_result = $stockvell_stmt->fetchAll(\PDO::FETCH_ASSOC);



            $total_members = count($stockvell_result);
            $members = [];
            $i = 0;
            while ($i < $total_members) {
                $new_single_member = array(
                    "id" => $stockvell_result[$i]['member_id'],
                    "firstname" => $stockvell_result[$i]['firstname'],
                    "surname" => $stockvell_result[$i]['surname'],
                    "profession" => $stockvell_result[$i]['profession'],
                    "country" => $stockvell_result[$i]['country'],
                );
                array_push($members, $new_single_member);
                $i++;
            }

            $single_stockvell = $stockvell_result[0];
            $new_result = [
                'id' => $single_stockvell["stockvell_id"],
                'name' => $single_stockvell["name"],
                'description' => $single_stockvell["description"],
                'link' => $single_stockvell["link"],
                'agreement' => $single_stockvell["agreement"],
                'status' => $single_stockvell["status"],
                'goal' => $single_stockvell["goal"],
                'leader_id' => $single_stockvell["leader_id"],
                'withdraw_member_id' => $single_stockvell["withdraw_member_id"],
                'category' => $single_stockvell["category"],
                'max_member' => $single_stockvell["max_member"],
                'payment' => $single_stockvell["payment"],
                'currency' => $single_stockvell["currency"],
                'payment_frequency' => $single_stockvell["payment_frequency"],
                'withdraw_frequency' => $single_stockvell["withdraw_frequency"],
                'withdraw_at' => $single_stockvell["withdraw_at"],
                'total_members' => $total_members,
                'members' => $members,
            ];

            // echo $new_result['members'];
            // var_dump($new_result['members']);
            // exit();
            return $new_result;
        } catch (\PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
        }
        return null;
    }


    public function getAllPendingStockvell($status, $is_admin, $member_id)
    {
        if ($is_admin) {
            // All stockvell of a member
            $sql = "SELECT s.id, s.leader_id, s.name, s.agreement, s.goal, s.category, s.max_member, s.status, s.payment, s.currency, s.payment_frequency, s.withdraw_frequency FROM stockvells s WHERE status=:status";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('status', $status);
        } else {
            // All stockvell of a member

            $sql = "SELECT sm.id, s.name, s.status, s.goal, s.category,  s.max_member, s.payment, s.currency, s.payment_frequency, s.withdraw_frequency FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id WHERE sm.member_id=:member_id AND status=:status";

            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('status', $status, \PDO::PARAM_STR);
            $stmt->bindParam('member_id', $member_id, \PDO::PARAM_INT);
        }
        $stmt->execute();
        $stockvell_result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $stockvell_result;
    }




    public function getStockvellByStatus($status)
    {
        $sql = "SELECT * FROM stockvells WHERE status=:status";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }


    // AASTHM = get all approved stockvells that has members
    public function getAASTHM($status)
    {
        $sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm 
        LEFT JOIN stockvells s ON sm.stockvell_id=s.id 
        WHERE s.status=:status 
        GROUP BY sm.stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // echo json_encode($result);


        return $result;
    }

    public function getAllApprovedStockvellOfAMember($member_id)
    {
        $sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.currency, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, sm.member_id, m.firstname FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id = s.id LEFT JOIN members m ON sm.member_id=m.id WHERE sm.member_id=:member_id AND status=:status";
        $stmt = $this->connect()->prepare($sql);
        $approved = "APPROVED";
        $stmt->bindParam('status', $approved);
        $stmt->bindParam('member_id', $member_id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllCoseStockvellOfAMember($member_id)
    {
        try {
            //code...
            $sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.currency, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, sm.member_id, m.firstname FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id = s.id LEFT JOIN members m ON sm.member_id=m.id WHERE sm.member_id=:member_id AND status=:status";
            $stmt = $this->connect()->prepare($sql);
            $close_requested = "CLOSE_REQUESTED";
            $stmt->bindParam('status', $close_requested);
            $stmt->bindParam('member_id', $member_id);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\PDOException $err) {
            //throw $th;
            echo $err->getMessage();
        }
    }
}
