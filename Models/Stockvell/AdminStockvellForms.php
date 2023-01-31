<?php

namespace Models\Stockvell;

use Config\Database;
use Exception;
use Utils\SendEmail;
use Utils\HTMLMessage;
use Models\Member\FetchMember;
use Models\Stockvell\Stockvell;
use Models\Stockvell\StockvellForms;
use Models\Stockvell\FetchStockvell;

class AdminStockvellForms extends Stockvell
{
    public function __construct()
    {
        $this->email_controler = new SendEmail();
        $this->member_fetch = new FetchMember();
    }
    public function approveStockvellByAdmin($stockvell_id, $leader_id)
    {
        try {
            // Set withdraw member id and set withdraw date
            // Get single pack and get withdraw frequency from that
            // withdraw_frequency
            $first_member_of_pack = $this->getFirstMemberOfAStockvellPack($stockvell_id);
            // Update stockvell
            $input_list = array("status" => "APPROVED");
            if ($first_member_of_pack) {
                $single_stockvell = $this->getSingleStockvell($stockvell_id);
                $first_widthdraw_member_id = null;
                $withdraw_frequency = 7;
                if ($first_member_of_pack->member_id) {
                    $first_widthdraw_member_id = $first_member_of_pack->member_id;
                }
                if ($single_stockvell->withdraw_frequency) {
                    $withdraw_frequency = $single_stockvell->withdraw_frequency;
                }
                $withdraw_date = date("Y-m-d");
                $offsetted_widthdraw_date =  date('Y-m-d', strtotime($withdraw_date . ' + ' . $withdraw_frequency . ' days'));
                $input_list["withdraw_member_id"] = $first_widthdraw_member_id;
                $input_list['withdraw_at'] = $offsetted_widthdraw_date;
            }
            $cols = array();
            // Remove blank inputs and password2
            foreach ($input_list as $key => $val) {
                if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
            }
            $updateElement = implode(', ', $cols);


            /// Update stockvells 
            $sql = "UPDATE stockvells SET $updateElement WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam('stockvell_id', $stockvell_id);

            if (!$stmt->execute()) {
                header('Location: /admin/?error=stmtfailed');
                exit();
            }

            // $leader_id
            $find_member = $this->member_fetch->findMemberByID($leader_id, null);
            $find_member_email = $find_member->email;
            $leader_name = $find_member->firstname . ' ' . $find_member->surname;
            $html_msg = new HTMLMessage();
            $html_body = $html_msg->becomeOwner($leader_name);
            $this->email_controler->sendMessage($find_member_email, $html_body, "Became stockvel leader");


            // make admin of the pack
            if ($first_member_of_pack) {
                $stockvell_form = new StockvellForms();
                if ($stockvell_form->updateMemberToLeader($stockvell_id, $first_member_of_pack->member_id)) {
                    header('Location: /admin/?error=none');
                    exit();
                } else {
                    header('Location: /admin/?error=stmtfailed');
                    exit();
                }
            }
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

        try {
            // Send messages to the members
            // find all members email and send message
            // $member_name = null, $leader_name=null, $today_date = null, $total_amount=null, $member_num, $total_members=null
            // getASingleApprovedStockvellWithMembers
            $f_stockvell = new FetchStockvell();
            $single_stockvell = $f_stockvell->getASingleApprovedStockvellWithMembers($stockvell_id);
            $started_date = $single_stockvell['start_at'];
            $ended_date = $single_stockvell['end_at'];
            $monthly_payment = $single_stockvell['payment'];
            $payment_currency = $single_stockvell['currency'];
            $withdraw_frequency = $single_stockvell['withdraw_frequency'];
            $leader_id = $single_stockvell['leader_id'];
            $pack_leader = $this->member_fetch->findMemberByID($leader_id);

            $members = $single_stockvell['members'];


            // Calculating the difference in timestamps
            $diff = strtotime($started_date) - strtotime($ended_date);
            $num_of_days = abs(round($diff / 86400)); // 24 * 60 * 60 = 86400 seconds // 1 day = 24 hours
            $num_of_payment = floor($num_of_days / $payment_currency);
            $total_amount = $num_of_payment * $monthly_payment;
//            echo json_encode(array("start" => $started_date, "end" => $ended_date, "monthly_payment" => $monthly_payment, "currency" => $payment_currency, "total_amounts" => $total_amount, "withdraw" => $withdraw_frequency, "num_of_days" => $num_of_days, "num_of_payment" => $num_of_payment, "members" => $members, "members_email" => $member_emails));

            $member_emails = [];
            $html_msg = new HTMLMessage();
            for ($i=0; $i < count($members); $i++) {
                array_push($member_emails, $members[$i]['email']);

                $html_str = $html_msg->packClosingSms($members[$i]['firstname'] . " " . $members[$i]['surname'], $pack_leader->firstname . " " . $pack_leader->surname, date('d-m-y'), $total_amount, $i+1, $members);
                $this->email_controler->sendMessage($members[$i]['email'], $html_str, "PARTICIPATION CERTIFICATE FOR CONTRIBUTION");
            }



            ///// Update element
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
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }



    public function addMemberToStockvell($stockvell_id, $member_id)
    {
        try {
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
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
