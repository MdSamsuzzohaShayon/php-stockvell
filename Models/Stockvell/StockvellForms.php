<?php

namespace Models\Stockvell;


use Config\Database;
use Models\Member\MemberForms;

class StockvellForms extends Database
{

    // public function __construct()
    // {
    //     // parent::__construct($member_email, $leader_id);
    //     // $this->leader_id = $_SESSION['member_id'];
    //     // admin/ = $redirect;
    // }

    public function __construct()
    {
        $this->ROOT = $_SERVER['DOCUMENT_ROOT'];
    }

    public function setStockvellPropertyArray($stockvell_id, $name, $description, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement)
    {
        $this->stockvell_id = $stockvell_id;
        $this->name = $name;
        $this->description = $description;
        $this->payment = $payment;
        $this->payment_frequency = $payment_frequency;
        $this->category = $category;
        $this->withdraw_frequency = $withdraw_frequency;
        $this->agreement = $agreement;

        return $this->input_list = array(
            "stockvell_id" => $stockvell_id,
            "name" => $name,
            "description" => $description,
            "payment" => $payment,
            "payment_frequency" => $payment_frequency,
            "category" => $category,
            "withdraw_frequency" => $withdraw_frequency,
            "agreement" => $agreement
        );
    }

    public function setStockvell($name, $goal, $payment, $description, $payment_frequency, $category, $withdraw_frequency, $agreement)
    {
        $this->name = $name;
        $this->goal = $goal;
        $this->payment = $payment;
        $this->description = $description;
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
            if (!empty($val) && $key === "stockvell_id") {
                continue;
            }
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }

        $updateElement = implode(', ', $cols);




        /// Update element 
        $sql = "UPDATE stockvells SET $updateElement WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('stockvell_id', $stockvell_id);


        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }




    private function createStockvellPack($redirect)
    {
        $conn= $this->connect();
        $conn->beginTransaction();

        try {
            $sql = "INSERT INTO stockvells(name, agreement, goal, category, status, description, payment, payment_frequency, withdraw_frequency) VALUES 
                                        (:name, :agreement, :goal, :category, :status, :description, :payment, :payment_frequency, :withdraw_frequency)";
            $stmt = $conn->prepare($sql);
    
            $stmt->bindParam('name', $this->name, \PDO::PARAM_STR);
            $agreement = preg_replace('/\s+/', '', $this->agreement);
            $stmt->bindParam('agreement', $agreement);
            $stmt->bindParam('goal', $this->goal, \PDO::PARAM_STR);
            $stmt->bindParam('category', $this->category, \PDO::PARAM_STR);
            $status = "PENDING";
            $stmt->bindParam('status', $status, \PDO::PARAM_STR);
            $stmt->bindParam('description', $this->description, \PDO::PARAM_STR);
            $stmt->bindParam('payment', intval($this->payment, 10), \PDO::PARAM_INT);
            $stmt->bindParam('payment_frequency', intval($this->payment_frequency, 10), \PDO::PARAM_INT);
            $stmt->bindParam('withdraw_frequency', intval($this->withdraw_frequency, 10), \PDO::PARAM_INT);
            //code...
            if (!$stmt->execute()) {
                header("Location: $redirect/?error=stmtfailed");
                exit();
            }
            $stockvell_id = $conn->lastInsertId();
            $conn->commit();
            // $stmt->execute();
            // $stmt = null;
            return $stockvell_id;
        } catch (\PDOException $e) {
            //throw $th;
            $conn->rollback();
            header("Location: $redirect/?error=stmtfailed");
            exit();
        }
    }

    public function validateAndCreate()
    {

        if (empty($this->name) || empty($this->goal) || empty($this->payment) || empty($this->description) || empty($this->payment_frequency) || empty($this->category) || empty($this->withdraw_frequency) || empty($this->agreement)) {
            header("Location: /admin/?error=emptyinput");
            exit();
        }

        if($this->createStockvellPack("/admin")){
            header("Location: /admin/?error=none");
            exit();
        }
    }

    public function createStockvellPackByMember($member_id, $address_proof, $govt_id_proof)
    {
        // echo json_encode(array(
        //     "name" => $this->name,
        //     // "agreement" => $this->agreement,
        //     "goal" => $this->goal,
        //     "category" => $this->category,
        //     "description" => $this->description,
        //     "payment" => intval($this->payment, 10),
        //     "payment_frequency" => intval($this->payment_frequency, 10),
        //     "category" => $this->category,
        //     "withdraw_frequency" => intval($this->withdraw_frequency, 10),
        //     "address_proof" => $address_proof["name"],
        //     "govt_id_proof" => $govt_id_proof["name"],
        // ));
        // exit();

        if (empty($this->name) || empty($this->goal) || empty($this->payment) || empty($this->description) || empty($this->payment_frequency) || empty($this->category) || empty($this->withdraw_frequency) || empty($this->agreement) || empty($member_id) || empty($govt_id_proof["name"]) || empty($address_proof["name"])) {
            header("Location: /dashboard/?error=emptyinput");
            exit();
        }

        
        
        $stockvell_id = $this->createStockvellPack("/dashboard");
        


        $member_controler = new MemberForms();
        $member_controler->joinTheStockvellPack($member_id, $stockvell_id);
        $unique_govt_id_name = $member_controler->uploadFileToServer($govt_id_proof, $this->ROOT, "/dashboard/?stockvell_id=$stockvell_id&");
        $unique_address_name = $member_controler->uploadFileToServer($address_proof, $this->ROOT, "/dashboard/?stockvell_id=$stockvell_id&");




        if ($member_controler->requestToBeTheLeader($member_id, $stockvell_id, $unique_govt_id_name, $unique_address_name)) {
            header("Location: /dashboard/?stockvell_id=$stockvell_id&error=none");
            exit();
        } else {
            header("Location: /dashboard/?stockvell_id=$stockvell_id&error=stmtfaild");
            exit();
        }
    }


    protected function removeFromLeaderRequest($sm_id)
    { // sm = stockvell leader request member (stockvell_lr_member)
        $sql = "DELETE FROM stockvell_lr_member WHERE id=:sm_id";
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute(array('sm_id' => $sm_id))) {
            return true;
        } else {
            return false;
        }
    }


    protected function findRequestOfMemberToBeLeaderByID($sm_id)
    {
        $sql = "SELECT * FROM stockvell_lr_member WHERE id=:sm_id";
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute(array('sm_id' => $sm_id))) {
            $result = $stmt->fetch(\PDO::FETCH_OBJ);
            return $result;
        } else {
            return null;
        }
    }

    // government ID proof and address proof
    protected function deletePrevFileFromServer($prev_name, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        if (file_exists($target_dir . $prev_name)) {
            unlink($target_dir . $prev_name);
        }
    }

    protected function updateMemberToLeader($stockvell_id, $member_id)
    {
        $sql = "UPDATE stockvells SET leader_id=:leader_id WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute(array(":leader_id" => $member_id, ":stockvell_id" => $stockvell_id))) {
            return true;
        }
        return false;
    }


    public function makeLeaderOfThePack($sm_id, $member_id, $stockvell_id)
    {
        if (empty($sm_id) || empty($member_id) || empty($stockvell_id)) {
            header("Location: /pack_single/?stockvell_id=$stockvell_id");
            exit();
        }
        $rl_result =  $this->findRequestOfMemberToBeLeaderByID($sm_id); // $rl = request leader
        if ($rl_result === null) {
            header("Location: /pack_single/?stockvell_id=$stockvell_id&error=stockvellnotfound");
            exit();
        }
        $this->deletePrevFileFromServer($rl_result->govt_id_proof, $this->ROOT);
        $this->deletePrevFileFromServer($rl_result->address_proof, $this->ROOT);

        $this->updateMemberToLeader($stockvell_id, $member_id);


        if (!$this->removeFromLeaderRequest($sm_id)) {
            header("Location: /pack_single/?stockvell_id=$stockvell_id&error=stmtfailed");
            exit();
        }

        header("Location: /pack_single/?stockvell_id=$stockvell_id&error=none");
        exit();
    }
}
