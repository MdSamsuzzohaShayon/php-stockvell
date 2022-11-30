<?php

namespace Models\Member;

use Config\Database;
use Utils\SendEmail;

use Models\Member\Member;
use Models\Stockvell\StockvellForms;

class MemberForms extends Member
{
    public function __construct()
    {
        $this->ROOT = $_SERVER['DOCUMENT_ROOT'];
    }

    public function setMember($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $source, $city)
    {
        $this->firstname = $firstname;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->password2 = $password2;
        $this->country = $country;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->profession = $profession;
        $this->interest = $interest;
        $this->source = $source;
        $this->city = $city;
    }


    public function verifyByAdmin($member_id)
    {
        $sql = "UPDATE members SET is_verified=:is_verified WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        $is_verified = 1;
        $stmt->bindParam('is_verified', $is_verified);
        if ($stmt->execute()) {
            header('Location: /admin');
            exit();
        }
        header('Location: /admin/?error=stmtfailed');
        exit();
    }


    protected function findMemberByEmail($email, $redirect_url)
    {
        $stmt = $this->connect()->prepare("SELECT id, firstname, surname, email  FROM members WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /$redirect_url?error=stmtfailed");
            exit();
        }
        $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
        if (!$member_found) {
            $stmt = null;
            header("Location: /$redirect_url?error=usernotfound");
            exit();
        }
        return $member_found;
    }

    public function updateDynamicMember($member_id, $redirect_url)
    {
        if (!empty($this->email)) {
            $_SESSION["member_email"] = $this->email;
        }
        if (!empty($this->password)) {
            if ($this->password !== $this->password2) {
                header("Location: /$redirect_url?error=passwordnotmatch");
                exit();
            }
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password = $hashedPassword;
        }

        // $unique_file_name = null;
        // if ($this->govt_id['name']) {
        //     $file_sql = "SELECT * FROM members WHERE id=:member_id";
        //     $stmt = $this->connect()->prepare($file_sql);
        //     $stmt->bindParam('member_id', $member_id);
        //     $stmt->execute();
        //     $found_file = $stmt->fetch(\PDO::FETCH_OBJ);
        //     if ($found_file->govt_id) {
        //         $this->deletePrevFileFromServer($found_file->govt_id, $this->ROOT);
        //     }
        //     $unique_file_name = $this->uploadFileToServer($this->govt_id, $this->ROOT, "/dashboard/?");
        // }
        $this->input_list = array(
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'email' => $this->email,
            'password' => $this->password,
            'password2' => $this->password2,
            'country' => $this->country,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'profession' => $this->profession,
            'interest' => $this->interest,
            'source' => $this->source,
            'city' => $this->city,
        );




        $cols = array();

        // Remove blank inputs and password2
        foreach ($this->input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }
        if ($this->updateMember($cols, $member_id)) {
            header("Location: /edit_member/?member_id=$member_id&error=none");
            exit();
        } else {
            header("Location: /edit_member/?member_id=$member_id&error=stmtfailed");
            exit();
        }
    }

    public function memberJoinPack($member_id, $stockvell_id, $redirect_url)
    {
        if (empty($member_id) || empty($stockvell_id)) {
            header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=emptyinput");
            exit();
        }

        $stockvell_detail = $this->membersLimitationExceed($stockvell_id);
        // echo json_encode(array($stockvell_detail));

        if (intval($stockvell_detail->total_members) === 0) {
            $first_widthdraw_member_id = intval($member_id, 10);
            $withdraw_frequency = 7;
            $input_list = [];
            if (intval($stockvell_detail->withdraw_frequency)) {
                $withdraw_frequency = intval($stockvell_detail->withdraw_frequency);
            }
            // Set withdraw date automitically
            $withdraw_date = date("Y-m-d");
            $offsetted_widthdraw_date =  date('Y-m-d', strtotime($withdraw_date . ' + ' . $withdraw_frequency . ' days'));
            $input_list["withdraw_member_id"] = $first_widthdraw_member_id;
            $input_list['withdraw_at'] = $offsetted_widthdraw_date;
            $stockvell_forms = new StockvellForms();
            $stockvell_forms->updateStockvell($stockvell_id, $input_list);
        }
        // echo "C - " . (intval($stockvell_detail->total_members ) === 0);
        // echo intval($stockvell_detail->withdraw_frequency) === 15;
        // exit();
        if ($stockvell_detail) {
            if ($stockvell_detail->max_member <= $stockvell_detail->total_members) {
                header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=reachlimit");
                exit();
            }
        }

        $find_relation = $this->findByMemberStockvellRelation($member_id, $stockvell_id);
        if ($find_relation) {
            // error - return or redirect
            header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=alreadymember");
            exit();
        } else {
            // create a new relation 
            if ($this->joinTheStockvellPack($member_id, $stockvell_id)) {
                header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=none");
                exit();
            } else {
                header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=stmtfailed");
                exit();
            }
        }
    }

    public function submitLeaderRequest($member_id,  $stockvell_id, $govt_id_proof, $address_proof)
    {
        if (empty($member_id) || empty($stockvell_id)) {
            header("Location: /pack_single/?stockvel_id=$stockvell_id&error=emptyinput");
            exit();
        }
        // echo json_encode(
        //     array(
        //         "member_id" => $member_id,
        //         "stockvell_id" => $stockvell_id,
        //         "govt_id_proof" => $govt_id_proof,
        //         "address_proof" => $address_proof
        //     )
        // );
        // exit();
        $unique_govt_id_name = $this->uploadFileToServer($govt_id_proof, $this->ROOT, "/pack_single/?stockvel_id=$stockvell_id&");
        $unique_address_name = $this->uploadFileToServer($address_proof, $this->ROOT, "/pack_single/?stockvel_id=$stockvell_id&");
        // echo json_encode(array($unique_govt_id_name, $unique_address_name));
        // exit();
        if ($this->requestToBeTheLeader($member_id, $stockvell_id, $unique_govt_id_name, $unique_address_name)) {
            header("Location: /pack_single/?stockvel_id=$stockvell_id&error=none");
            exit();
        } else {
            header("Location: /pack_single/?stockvel_id=$stockvell_id&error=stmtfaild");
            exit();
        }
    }

    public function resignLeadership($stockvell_id, $reqirect_url)
    {
        echo "Working";
        if (empty($stockvell_id)) {
            header("Location: $reqirect_url/?stockvel_id=$stockvell_id&error=emptyinput");
            exit();
        }

        try {
            //code...
            $sql = "UPDATE stockvells SET leader_id=NULL WHERE id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);


            $stmt->execute(array('stockvell_id' => $stockvell_id));
            header("Location: $reqirect_url/?stockvel_id=$stockvell_id&error=none");
            exit();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
            //throw $th;
        }
    }

    public function memberLeavePack($member_id, $stockvell_id, $redirect_url)
    {
        if (empty($member_id) || empty($stockvell_id)) {
            header("Location: /pack_single/?stockvel_id=$stockvell_id&error=emptyinput");
            exit();
        }
        $find_relation = $this->findByMemberStockvellRelation($member_id, $stockvell_id);
        if ($find_relation) {
            // remove from relation 
            if ($this->leaveFromStockvellPack($member_id, $stockvell_id)) {
                header("Location: /pack_single/?stockvel_id=$stockvell_id&error=none");
                exit();
            } else {
                header("Location: /pack_single/?stockvel_id=$stockvell_id&error=stmtfailed");
                exit();
            }
        } else {
            // error - return or redirect
            header("Location: /pack_single/?stockvel_id=$stockvell_id&error=alreadymember");
            exit();
        }
    }


    public function memberApproveToPack($member_id, $stockvell_id, $redirect_url = '/')
    {
        if (empty($member_id) || empty($stockvell_id)) {
            header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=emptyinput");
            exit();
        }
        try {
            $sql = "UPDATE stockvell_to_member SET status=:status WHERE member_id=:member_id AND stockvell_id=:stockvell_id";
            $stmt = $this->connect()->prepare($sql);
            $new_status = "APPROVED";
            if ($stmt->execute(array('status' => $new_status, "member_id" => $member_id, "stockvell_id" => $stockvell_id))) {
                header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=none");
                exit();
            } else {
                header("Location: $redirect_url/?stockvel_id=$stockvell_id&error=stmtfailed");
                exit();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
    }
}
