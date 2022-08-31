<?php
class Stockvell extends Database
{
    // private $member_email;
    public function __construct($member_email, $leader_id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->member_email = $member_email;
        $this->leader_id = $leader_id;
    }


    public function getCurrentMember()
    {
        $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role FROM members WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('email', $this->member_email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        // var_dump($result);
        return $result;
    }

    public function getAllPendingStockvell($status)
    {
        // All stockvell of a member
        $sql = "SELECT * FROM stockvells WHERE leader_id=:leader_id AND status=:status";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('leader_id', $this->leader_id);
        $stmt->bindParam('status', $status);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    public function getAllApprovedStockvell($status)
    {
        // All stockvell of a member
        // $sql = "SELECT * FROM stockvells WHERE leader_id=:leader_id AND status=:status";
        $sql = "SELECT s.id, s.goal, s.category, s.payment, s.payment_frequency, s.withdraw_frequency, s.name, s.status, sm.stockvell_id, COUNT(sm.stockvell_id) as totel_members FROM stockvell_to_member sm 
            LEFT JOIN stockvells s ON sm.stockvell_id=s.id 
            WHERE s.status=:status 
            GROUP BY sm.stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }
}


class StockvellForms extends Database
{

    public function __construct($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement)
    {
        // parent::__construct($member_email, $leader_id);
        $this->leader_id = $_SESSION['member_id'];
        $this->name = $name;
        $this->goal = $goal;
        $this->payment = $payment;
        $this->payment_frequency = $payment_frequency;
        $this->category = $category;
        $this->withdraw_frequency = $withdraw_frequency;
        $this->agreement = $agreement;
    }



    public function updateStockvell($stockvell_id, $input_list)
    {
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
            header('Location: /admin.php?error=stmtfailed');
            exit();
        }

        header('Location: /admin.php?error=none');
    }




    private function createStockvellPack()
    {
        $sql = "INSERT INTO stockvells(name, agreement, goal, category, status, payment, payment_frequency, withdraw_frequency, leader_id) VALUES (:name, :agreement, :goal, :category, :status, :payment, :payment_frequency, :withdraw_frequency, :leader_id)";
        $stmt = $this->connect()->prepare($sql);

        $stmt->bindParam('name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam('agreement', $this->agreement);
        $stmt->bindParam('goal', $this->goal, PDO::PARAM_STR);
        $stmt->bindParam('category', $this->category, PDO::PARAM_STR);
        $status = "PENDING";
        $stmt->bindParam('status', $status, PDO::PARAM_STR);
        $stmt->bindParam('payment', intval($this->payment, 10), PDO::PARAM_INT);
        $stmt->bindParam('payment_frequency', intval($this->payment_frequency, 10), PDO::PARAM_INT);
        $stmt->bindParam('withdraw_frequency', intval($this->withdraw_frequency, 10), PDO::PARAM_INT);
        $stmt->bindParam('leader_id', intval($this->leader_id, 10), PDO::PARAM_INT);

        // echo json_encode(array(
        //     "name" => $this->name,
        //     "agreement" => $this->agreement,
        //     "goal" => $this->goal,
        //     "category" => $this->category,
        //     "payment" => intval($this->payment, 10),
        //     "payment_frequency" => intval($this->payment_frequency, 10),
        //     "category" => $this->category,
        //     "withdraw_frequency" => intval($this->withdraw_frequency, 10),
        //     "leader_id" => intval($this->leader_id, 10),
        // ));
        // exit();


        // make many to many relationship


        try {
            //code...
            if (!$stmt->execute()) {
                $stmt = null;
                header("Location: /dashboard.php?error=stmtfailed");
                exit();
            }
            // $stmt->execute();
            // $stmt = null;
            header("Location: /dashboard.php?error=none");
        } catch (PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
            header("Location: /dashboard.php?error=stmtfailed");
        }
    }

    public function validateAndCreate()
    {

        if (empty($this->name) || empty($this->goal) || empty($this->payment) || empty($this->payment_frequency) || empty($this->category) || empty($this->withdraw_frequency) || empty($this->agreement)) {
            header("Location: /dasboard.php?error=emptyinput");
            exit();
        }

        $this->createStockvellPack();
    }
}
