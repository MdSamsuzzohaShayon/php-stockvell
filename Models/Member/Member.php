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
        $stmt = $this->connect()->prepare("SELECT id, firstname, surname email  FROM members WHERE email = :email ");

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
        $stmt = $this->connect()->prepare("SELECT id, firstname, phone, email  FROM members WHERE phone = :phone;");

        if (!$stmt->execute(array("phone"=> $phone))) {
            $stmt = null;
            header("Location: /$redirect_url?error=stmtfailed");
            exit();
        }
        $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
        // var_dump($member_found);
        // exit();
        return $member_found;
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
        if($stmt->execute()){
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

    public function uploadFileToServer($uploadedFile, $ROOT, $is_member)
    {
        $target_dir = $ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $uploadedFile["name"]);
        $imageFileType = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($unique_file_name);
        $err_redirect = "/signup";
        if ($is_member === true) {
            $err_redirect = '/dashboard';
        } else {
            $err_redirect = "/signup";
        }

        // 1kb = 1000, 1 mb = 1000 kb
        if ($uploadedFile['error'] === true || $uploadedFile['error'] === 1) {
            header("Location: $err_redirect/?error=invalidfile");
            exit();
        }
        if ($uploadedFile['size'] > (1000 * 1000 * 2)) {
            header("Location: $err_redirect/?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: $err_redirect/?error=invalidfile");
            exit();
        }
        // echo json_encode($uploadedFile);
        // exit();
        // Upload file
        // echo $target_file;
        move_uploaded_file($uploadedFile["tmp_name"], $target_file);
        // echo "uploads/" . $uploadedFile["name"];
        return $unique_file_name;
    }



}







