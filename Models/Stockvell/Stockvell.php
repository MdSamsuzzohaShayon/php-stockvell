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

    public function getAllPendingStockvell($status, $is_admin)
    {
        $sql = null;
        $stmt = null;
        if ($is_admin) {
            // All stockvell of a member
            $sql = "SELECT s.id, s.leader_id, s.name, s.agreement, s.goal, s.category, s.status, s.payment, s.payment_frequency, s.withdraw_frequency, m.firstname, m.surname FROM stockvells s LEFT JOIN members m ON s.leader_id=m.id  WHERE status=:status";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('status', $status);
        } else {
            // All stockvell of a member
            $sql = "SELECT * FROM stockvells WHERE leader_id=:leader_id AND status=:status";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('leader_id', $this->leader_id);
            $stmt->bindParam('status', $status);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
    // getAllApprovedStockvellOfAMember - reverse left join and group by of this function
    public function getASingleStockvell($stockvell_id)
    {
        // $stockvell_sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm 
        // LEFT JOIN stockvells s ON sm.stockvell_id=s.id 
        // WHERE sm.stockvell_id=:stockvell_id
        // GROUP BY sm.stockvell_id";
        $stockvell_sql = "SELECT sm.id, sm.stockvell_id, s.agreement, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status,
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
            'agreement' => $single_stockvell["agreement"],
            'status' => $single_stockvell["status"],
            'goal' => $single_stockvell["goal"],
            'leader_id' => $single_stockvell["leader_id"],
            'category' => $single_stockvell["category"],
            'payment' => $single_stockvell["payment"],
            'payment_frequency' => $single_stockvell["payment_frequency"],
            'withdraw_frequency' => $single_stockvell["withdraw_frequency"],
            'total_members' => $total_members,
            'members' => $members,
        ];

        // echo $new_result['members'];
        // var_dump($new_result['members']);
        return $new_result;
    }


    public function getAllApprovedStockvell($status)
    {
        $sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm 
        LEFT JOIN stockvells s ON sm.stockvell_id=s.id 
        WHERE s.status=:status 
        GROUP BY sm.stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllApprovedStockvellOfAMember($status, $member_id)
    {
        $sql = "SELECT s.id, s.goal, s.leader_id, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, sm.member_id, m.firstname FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id = s.id LEFT JOIN members m ON sm.member_id=m.id WHERE sm.member_id=:member_id AND status=:status";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        $stmt->bindParam('member_id', $member_id);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
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
}







