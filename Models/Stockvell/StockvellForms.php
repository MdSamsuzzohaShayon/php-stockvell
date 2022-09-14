<?php

namespace Models\Stockvell;

use Config\Database;

class StockvellForms extends Database
{

    // public function __construct()
    // {
    //     // parent::__construct($member_email, $leader_id);
    //     // $this->leader_id = $_SESSION['member_id'];
    //     // $this->redirect = $redirect;
    // }

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
            if(!empty($val) && $key === "stockvell_id"){
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




    private function createStockvellPack()
    {
        $sql = "INSERT INTO stockvells(name, agreement, goal, category, status, description, payment, payment_frequency, withdraw_frequency) VALUES 
                                    (:name, :agreement, :goal, :category, :status, :description, :payment, :payment_frequency, :withdraw_frequency)";
        $stmt = $this->connect()->prepare($sql);

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
        // $stmt->bindParam('leader_id', intval($this->leader_id, 10), \PDO::PARAM_INT);

        /*
        echo json_encode(array(
            "name" => $this->name,
            // "agreement" => $this->agreement,
            "goal" => $this->goal,
            "category" => $this->category,
            "description" => $this->description,
            "payment" => intval($this->payment, 10),
            "payment_frequency" => intval($this->payment_frequency, 10),
            "status" => $status,
            "category" => $this->category,
            "withdraw_frequency" => intval($this->withdraw_frequency, 10),
            "leader_id" => intval($this->leader_id, 10),
        ));
        exit();
        */


        // make many to many relationship


        try {
            //code...
            if (!$stmt->execute()) {
                header("Location: /$this->redirect?error=stmtfailed");
                exit();
            }
            // $stmt->execute();
            // $stmt = null;
            header("Location: /$this->redirect?error=none");
        } catch (\PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
            header("Location: /$this->redirect?error=stmtfailed");
        }
    }

    public function validateAndCreate()
    {

        if (empty($this->name) || empty($this->goal) || empty($this->payment) || empty($this->description) || empty($this->payment_frequency) || empty($this->category) || empty($this->withdraw_frequency) || empty($this->agreement)) {
            header("Location: /$this->redirect?error=emptyinput");
            exit();
        }

        $this->createStockvellPack();
    }
}
