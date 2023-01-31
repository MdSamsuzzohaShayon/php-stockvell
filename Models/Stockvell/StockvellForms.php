<?php

namespace Models\Stockvell;


use Config\Database;
use Models\Member\MemberForms;
use Models\Member\FetchMember;
use Models\Stockvell\Stockvell;
use Models\Stockvell\FetchStockvell;
use Utils\PeriodConvert;
use Utils\SendEmail;
use Utils\SendSMS;
use Utils\HTMLMessage;

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
        $this->email_controler = new SendEmail();
        $this->member_fetch = new FetchMember();
    }

    public function setStockvellPropertyArray($stockvel_id, $name, $payment, $currency,  $payment_frequency, $withdraw_frequency, $category, $max_member, $start_at, $end_at, $description, $agreement)
    {
        $period_freq = new PeriodConvert();
        $this->stockvell_id = $stockvel_id;
        $this->name = $name;
        $this->description = $description;
        $this->payment = $payment;
        $this->currency = $currency;
        $new_payment_freq = $period_freq->convertFromTextToInt($payment_frequency);
        $new_withdraw_freq = $period_freq->convertFromTextToInt($withdraw_frequency);
        $this->payment_frequency = $new_payment_freq;
        $this->withdraw_frequency = $new_withdraw_freq;
        $this->category = $category;
        $this->max_member = $max_member;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->agreement = $agreement;

        return $this->input_list = array(
            "stockvell_id" => $stockvel_id,
            "name" => $name,
            "description" => $description,
            "payment" => $payment,
            "currency" => $currency,
            "payment_frequency" => $new_payment_freq,
            "withdraw_frequency" => $new_withdraw_freq,
            "category" => $category,
            "max_member" => $max_member,
            "start_at" => $start_at,
            "end_at" => $end_at,
            "agreement" => $agreement
        );
    }

    public function setStockvell($name, $payment, $description, $payment_frequency, $category, $max_member, $withdraw_frequency, $agreement, $currency, $start_at, $end_at)
    {
        $period_freq = new PeriodConvert();
        $this->name = $name;
        $this->payment = $payment;
        $this->description = $description;
        $this->payment_frequency = $period_freq->convertFromTextToInt($payment_frequency);
        $this->category = $category;
        $this->max_member = $max_member;
        $new_withdraw_frequency = $period_freq->convertFromTextToInt($withdraw_frequency);
        $this->withdraw_frequency = $new_withdraw_frequency;
        $this->agreement = $agreement;
        $this->currency = $currency;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
    }



    public function updateStockvell($stockvell_id, $input_list)
    {
        try {
            //code...
            $cols = array();
            // Remove blank inputs and password2
            foreach ($input_list as $key => $val) {
                if (!empty($val) && $key === "stockvell_id") {
                    continue;
                }
                if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
            }

            $updateElement = implode(', ', $cols);
            // echo $updateElement;
            // exit();



            /// Update element 
            $sql = "UPDATE stockvells SET $updateElement WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('stockvell_id', $stockvell_id);


            if (!$stmt->execute()) {

                return false;
            }
            return true;
        } catch (\PDOException $e) {
            //throw $th;
            echo $e->getMessage();
            exit();
        }
    }



    public function generateStockvellLink($stockvell_id, $redirect_url)
    {
        // rand ( 10000 , 99999 );
        $random_digit = rand(1000, 9999);
        $view = 1; // edit = 2
        $link = strval($random_digit) . strval($view) . strval($stockvell_id);
        $updateElement = array("link" => $link);
        if ($this->updateStockvell($stockvell_id, $updateElement)) {
            header("Location: /$redirect_url?stockvell_id=$stockvell_id&error=none");
            exit();
        } else {
            header("Location: /$redirect_url?stockvell_id=$stockvell_id&error=smtpfailed");
            exit();
        }
    }

    public function setWithdrawMember($stockvell_id, $withdraw_member_id, $redirect_url)
    {
        $updateElement = array("withdraw_member_id" => intval($withdraw_member_id));
        if ($this->updateStockvell($stockvell_id, $updateElement)) {
            // Send message to the member who withdrawn
            $f_stockvell = new FetchStockvell();
            $single_stockvell = $f_stockvell->getASingleApprovedStockvellWithMembers($stockvell_id);
            $started_date = $single_stockvell['start_at'];
            $ended_date = $single_stockvell['end_at'];
            $monthly_payment = $single_stockvell['payment'];
            $payment_frequency = $single_stockvell['payment_frequency'];
            $payment_currency = $single_stockvell['currency'];
            $withdraw_frequency = $single_stockvell['withdraw_frequency'];
            $leader_id = $single_stockvell['leader_id'];
            $pack_leader = $this->member_fetch->findMemberByID($leader_id);
            $leader_name = $pack_leader->firstname . " " . $pack_leader->surname;
            $selected_member = $this->member_fetch->findMemberByID($withdraw_member_id);
            $member_name = $selected_member->firstname . " " . $selected_member->surname;
            $member_email = $selected_member->email;
            $members = $single_stockvell['members'];
            $total_members = count($members);
            $today_date = date('d-m-Y');
            // Calculating the difference in timestamps
            $diff = strtotime($started_date) - strtotime($ended_date);
            $num_of_days = abs(round($diff / 86400)); // 24 * 60 * 60 = 86400 seconds // 1 day = 24 hours
            $num_of_payment = floor($num_of_days / $payment_frequency);
            $total_amount = $num_of_payment * $monthly_payment;
            $num_of_withdraw = floor($num_of_days / $withdraw_frequency) ;
            $member_num = 0;
            for ($i=0; $i < count($members); $i++) {
                if($members[$i]["id"] === intval($withdraw_member_id, 10)){
                    $member_num = $i+1;
                }
            }
//            echo json_encode(array("start" => $started_date, "end" => $ended_date, "monthly_payment" => $monthly_payment, "currency" => $payment_currency, "total_amounts" => $total_amount, "withdraw" => $withdraw_frequency, "num_of_days" => $num_of_days, "num_of_payment" => $num_of_payment, "num_of_withdraw" => $num_of_withdraw, "member_name" => $member_name, "member_email"=> $member_email, "member_num"=>$member_num, "withdraw_member_id" => $withdraw_member_id, "members" => $members));
//            exit();
            $html_msg = new HTMLMessage();
            $html_str = $html_msg->withdrawMemberMsg($member_name, $monthly_payment, $today_date, $total_amount, $member_num, $num_of_withdraw, $leader_name, $total_members);
            $this->email_controler->sendMessage($member_email, $html_str, "PARTICIPATION CERTIFICATE FOR CASHING");

            header("Location: /$redirect_url?stockvell_id=$stockvell_id&error=none");
            exit();
        } else {
            header("Location: /$redirect_url?stockvell_id=$stockvell_id&error=smtpfailed");
            exit();
        }
    }




    // Get last member
    private function createStockvellPack($redirect)
    {
        $conn = $this->connect();
        $conn->beginTransaction();

        try {
            $sql = "INSERT INTO stockvells(name, agreement, category, max_member, status, description, payment, payment_frequency, withdraw_frequency, currency, start_at, end_at) VALUES 
                                        (:name, :agreement, :category, :max_member, :status, :description, :payment, :payment_frequency, :withdraw_frequency, :currency, :start_at, :end_at)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam('name', $this->name, \PDO::PARAM_STR);
            // $agreement = preg_replace('/\s+/', '', $this->agreement);
            $stmt->bindParam('agreement', $this->agreement);
            $stmt->bindParam('category', $this->category, \PDO::PARAM_STR);
            $stmt->bindParam('max_member', intval($this->max_member, 10), \PDO::PARAM_INT);
            $status = "PENDING";
            $stmt->bindParam('status', $status, \PDO::PARAM_STR);
            $stmt->bindParam('description', $this->description, \PDO::PARAM_STR);
            $stmt->bindParam('payment', intval($this->payment, 10), \PDO::PARAM_INT);
            $stmt->bindParam('currency', $this->currency, \PDO::PARAM_STR);
            $stmt->bindParam('start_at', $this->start_at);
            $stmt->bindParam('end_at', $this->end_at);
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

        if (empty($this->name) || empty($this->payment) || empty($this->currency) || empty($this->description) || empty($this->payment_frequency) || empty($this->category) || empty($this->max_member) || empty($this->withdraw_frequency) || empty($this->agreement)) {
            header("Location: /admin/?error=emptyinput");
            exit();
        }

        if ($this->createStockvellPack("/admin")) {
            header("Location: /admin/?error=none");
            exit();
        }
    }

    public function createStockvellPackByMember($member_id, $address_proof, $govt_id_proof)
    {
        try {
            // echo json_encode(array(
            //     "name" => $this->name,
            //     // "agreement" => $this->agreement,
            //     "category" => $this->category,
            //     "description" => $this->description,
            //     "payment" => intval($this->payment, 10),
            //     "payment_frequency" => intval($this->payment_frequency, 10),
            //     "category" => $this->category,
            //     "withdraw_frequency" => intval($this->withdraw_frequency, 10),
            //     "start_at" => $this->start_at,
            //     "end_at" => $this->end_at,
            //     "govt_id_proof" => $govt_id_proof["name"],
            //     "address_proof" => $address_proof["name"],
            //     "govt_id_proof" => $govt_id_proof["name"],
            // ));
            // exit();

            if (
                empty($this->name) || empty($this->payment) || empty($this->description) || empty($this->payment_frequency)
                || empty($this->category) || empty($this->max_member) || empty($this->withdraw_frequency) || empty($this->agreement)
                || empty($this->start_at) || empty($this->end_at)
                || empty($this->currency) || empty($member_id) || empty($govt_id_proof["name"]) || empty($address_proof["name"])
            ) {
                header("Location: /dashboard/?error=emptyinput");
                exit();
            }



            $stockvell_id = $this->createStockvellPack("/dashboard");



            $member_controler = new MemberForms();
            $member_controler->joinTheStockvellPack($member_id, $stockvell_id, true); // by default approved member
            $unique_govt_id_name = $member_controler->uploadFileToServer($govt_id_proof, $this->ROOT, "/dashboard/?stockvell_id=$stockvell_id&");
            $unique_address_name = $member_controler->uploadFileToServer($address_proof, $this->ROOT, "/dashboard/?stockvell_id=$stockvell_id&");


            if ($member_controler->requestToBeTheLeader($member_id, $stockvell_id, $unique_govt_id_name, $unique_address_name)) {
                $sms_controller = new SendSMS();
                $find_member = $this->member_fetch->findMemberByID($member_id, '/dashobard');
                if ($find_member) {
                    $find_member_email = $find_member->email;
                    $find_member_phone = $find_member->phone;
                    $pack_name = $this->name;
                    $pack_category = $this->category;
                    $pack_payment = $this->payment;
                    $backend_url = $_ENV['FRONTEND_URL'];

                    // Get last stockvell
                    // http://localhost/pack_single.php?stockvel_id=3

                    $html_body = "
                        <h1 >You have created a stockvell pack</h1>
                        <p>Name: $pack_name </p>
                        <p>Category: $pack_category </p>
                        <p>Payment: $pack_payment </p>
                        <a href='$backend_url/pack_single/?stockvel_id=$stockvell_id'>Link of the pack</a>
                    ";
                    $this->email_controler->sendMessage($find_member_email, $html_body, "Stockvel pack created");
                    $new_phone_num = str_replace("_", "", $find_member_phone);
                    $smsBody = "You have created a pack in Stockvel that is under review. URL: $backend_url/pack_single/?stockvel_id=$stockvell_id";
                    $sent_sms = $sms_controller->sendTwilioSMS($new_phone_num, $smsBody);
                    // if($sent_sms){
                    //     echo "<h1 class='alert alert-danger'>SMS is been sent</h1>";
                    //  }else{
                    //     echo "<h1 class='alert alert-danger'>SMS is not been sent</h1>";
                    //  }                 
                    // echo "Phone Number - " . $new_phone_num;
                    // exit();
                }
                // Send email and sms
                // $this->email_controler->sendMessage($this->)
                header("Location: /dashboard/?stockvel_id=$stockvell_id&error=none");
                exit();
            } else {
                header("Location: /dashboard/?stockvel_id=$stockvell_id&error=stmtfaild");
                exit();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
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

    public function updateMemberToLeader($stockvell_id, $member_id)
    {
        try {
            $sql = "UPDATE stockvells SET leader_id=:leader_id WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            if ($stmt->execute(array(":leader_id" => $member_id, ":stockvell_id" => $stockvell_id))) {
                return true;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
        return false;
    }


    public function requestToCloseStockvell($leader_id, $stockvell_id, $redirect_url)
    {
        // make status approved to close 
        try {
            //code...
            $sql = "UPDATE stockvells SET status=:status WHERE id=:stockvell_id AND leader_id=:leader_id";
            $stmt = $this->connect()->prepare($sql);
            $status = "CLOSE_REQUESTED";
            if ($stmt->execute(array(":status" => $status, ":stockvell_id" => $stockvell_id, ":leader_id" => $leader_id))) {
                header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=none");
                exit();
            } else {
                header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=smtpfailed");
                exit();
            }
        } catch (\PDOException $err) {
            // throw $err;
            echo $err->getMessage();
            exit();
        }
        header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=stockvellnotfound");
        exit();
    }


    public function makeLeaderOfThePack($sm_id, $member_id, $stockvell_id, $redirect_url)
    {
        if (empty($sm_id) || empty($member_id) || empty($stockvell_id)) {
            header("Location: /$redirect_url?stockvel_id=$stockvell_id");
            exit();
        }
        $rl_result =  $this->findRequestOfMemberToBeLeaderByID($sm_id); // $rl = request leader
        if ($rl_result === null) {
            header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=stockvellnotfound");
            exit();
        }
        $this->deletePrevFileFromServer($rl_result->govt_id_proof, $this->ROOT);
        $this->deletePrevFileFromServer($rl_result->address_proof, $this->ROOT);


        $this->updateMemberToLeader($stockvell_id, $member_id);


        $find_member = $this->member_fetch->findMemberByID($rl_result->member_id, null);
        $find_member_email = $find_member->email;
        $leader_name = $find_member->firstname . ' ' . $find_member->surname;
        $html_msg = new HTMLMessage();
        $html_body = $html_msg->becomeOwner($leader_name);
        $this->email_controler->sendMessage($find_member_email, $html_body, "Became stockvel leader");



        if (!$this->removeFromLeaderRequest($sm_id)) {
            header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=stmtfailed");
            exit();
        }

        header("Location: /$redirect_url?stockvel_id=$stockvell_id&error=none");
        exit();
    }



    // FIFO = First in first out 
    public function setMemberToWithdrawFIFO($stockvell_id, $previous_member_id)
    {
        $stockvell_control = new Stockvell();
        $next_withdraw_member = $stockvell_control->getNextMemberOfAStockvellPack($stockvell_id, $previous_member_id);
        if ($next_withdraw_member) {
            // Update withdraw member and date
            $withdraw_frequency = 7;
            $single_stockvell = $stockvell_control->getSingleStockvell($stockvell_id);
            if ($single_stockvell->withdraw_frequency) {
                $withdraw_frequency = $single_stockvell->withdraw_frequency;
            }
            $withdraw_date = date("Y-m-d");
            $offsetted_widthdraw_date =  date('Y-m-d', strtotime($withdraw_date . ' + ' . $withdraw_frequency . ' days'));
            $input_list = array("withdraw_member_id" => $next_withdraw_member->member_id, "withdraw_at" => $offsetted_widthdraw_date);
            // echo json_encode($input_list);
            // exit();
            $this->updateStockvell($stockvell_id, $input_list);
            // Send message to $next_withdraw_member
        } else {
            // close the stockvell pack 
            // echo "<div class='alert alert-primary'>All members withdrawn their money!</div>";
        }
    }
}
