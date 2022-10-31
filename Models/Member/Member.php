<?php

namespace Models\Member;

use Config\Database;




/**
 * @database operations
 */
class Member extends Database
{

    // Delete this function leter and use the below one
    protected function findMember($email)
    {
        $stmt = $this->connect()->prepare("SELECT id, firstname, surname, email  FROM members WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /login.php?error=stmtfailed");
            exit();
        }


        // Problem here
        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("Location: /login.php?error=usernotfound");
            exit();
        }


        $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
        return $member_found;
    }

    protected function membersLimitationExceed($stockvell_id){
        try {
            //code...
            $sql = "SELECT sm.stockvell_id, s.name, s.status, s.max_member, COUNT(sm.stockvell_id) AS total_members FROM stockvell_to_member sm LEFT JOIN stockvells s ON sm.stockvell_id=s.id  WHERE stockvell_id=:stockvell_id GROUP BY sm.stockvell_id;";
            $stmt = $this->connect()->prepare($sql);
            if (!$stmt->execute(array(':stockvell_id' => $stockvell_id))) {
                return null;
            }
            // var_dump(array("mid"=> $member_id, "sid"=> $stockvell_id));
            // exit();
            $stockvell_detail = $stmt->fetch(\PDO::FETCH_OBJ);
            
            if (!$stockvell_detail) return null;
            return $stockvell_detail;
        } catch (\PDOException $err) {
            echo $err->getMessage();
            //throw $th;
        }
    }
    



    protected function findByMemberStockvellRelation($member_id, $stockvell_id)
    {
        $sql = "SELECT * FROM stockvell_to_member  WHERE member_id=:member_id AND stockvell_id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute(array(':member_id' => $member_id, ':stockvell_id' => $stockvell_id))) {
            return null;
        }
        // var_dump(array("mid"=> $member_id, "sid"=> $stockvell_id));
        // exit();
        $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
        if (!$member_found) return null;
        return $member_found;
    }

    public function joinTheStockvellPack($member_id, $stockvell_id)
    {
        $sql = "INSERT INTO stockvell_to_member (member_id, stockvell_id) VALUES(:member_id, :stockvell_id)";
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute(array(':member_id' => $member_id, ':stockvell_id' => $stockvell_id))) {
            return false;
        }
        return true;
    }


    public function requestToBeTheLeader($member_id, $stockvell_id, $govt_id_proof, $address_proof)
    {
        $sql = "INSERT INTO stockvell_lr_member (member_id, stockvell_id, govt_id_proof, address_proof) VALUES(:member_id, :stockvell_id, :govt_id_proof, :address_proof)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":member_id", $member_id);
        $stmt->bindParam(":stockvell_id", $stockvell_id);
        $stmt->bindParam(":govt_id_proof", $govt_id_proof);
        $stmt->bindParam(":address_proof", $address_proof);
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }

    protected function leaveFromStockvellPack($member_id, $stockvell_id)
    {
        $sql = "DELETE FROM stockvell_to_member WHERE member_id=:member_id AND stockvell_id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute(array(':member_id' => $member_id, ':stockvell_id' => $stockvell_id))) {
            return false;
        }
        return true;
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
        // if (!$member_found) {
        //     $stmt = null;
        //     header("Location: /$redirect_url?error=usernotfound");
        //     exit();
        // }
        return $member_found;
    }


    protected function findMemberByPhone($phone, $redirect_url)
    {
        try {
            $stmt = $this->connect()->prepare("SELECT id, firstname, phone, email  FROM members WHERE phone = :phone;");
    
            if (!$stmt->execute(array("phone" => $phone))) {
                $stmt = null;
                header("Location: /$redirect_url?error=stmtfailed");
                exit();
            }
            $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
            return $member_found;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit();
        }
        return null;
    }






    protected function updateMember($cols, $member_id)
    {
        $updateElement = implode(', ', $cols);

        $sql = "UPDATE members SET $updateElement WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        // $stmt->bindParam('updateElement', $updateElement,\PDO::PARAM_STR); // Not working
        // try {
        //     if ($stmt->execute()) {
        //         // header("Location: /$redirect_url?error=none");
        //         echo "Working - " . $redirect_url;
        //         exit();
        //     }
        //     header("Location: /$redirect_url?error=stmtfailed");
        //     exit();
        // } catch (\PDOException $e) {
        //     //throw $th;
        //     header("Location: /$redirect_url?error=stmtfailed");
        //     exit();
        // }
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }




    public function deletePrevFileFromServer($prev_name, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        if (file_exists($target_dir . $prev_name)) {
            unlink($target_dir . $prev_name);
        }
    }

    public function uploadFileToServer($uploadedFile, $ROOT, $err_redirect)
    {
        $target_dir = $ROOT . "/uploads/";
        $uploaded_file_name = str_replace(' ', '_', $uploadedFile["name"]);
        $unique_file_name = basename("m_" . date("Ymd_"). rand(100, 999) . '_' . $uploaded_file_name);
        $imageFileType = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($unique_file_name);

        // echo $target_dir . "</br>";
        // echo $unique_file_name . "</br>";
        // echo $imageFileType . "</br>";
        // echo $target_file . "</br>";
        // exit();
        // $err_redirect = "/signup";
        // if ($is_member === true) {
        //     $err_redirect = '/dashboard';
        // } else {
        //     $err_redirect = "/signup";
        // }

        // 1kb = 1000, 1 mb = 1000 kb
        if ($uploadedFile['error'] === true || $uploadedFile['error'] === 1) {
            header("Location: " . $err_redirect . "error=invalidfile");
            exit();
        }
        // if ($uploadedFile['size'] > (1000 * 1000 * 2)) {
        //     header("Location: " . $err_redirect . "error=invalidfile");
        //     exit();
        // }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: " . $err_redirect . "error=invalidfile");
            exit();
        }
        // echo json_encode($uploadedFile);
        // exit();
        // echo $uploadedFile["tmp_name"] . $target_file . ' Uploaded';
        // Upload file
        // echo $target_file;
        move_uploaded_file($uploadedFile["tmp_name"], $target_file);
        // echo "uploads/" . $uploadedFile["name"];
        return $unique_file_name;
    }


}
