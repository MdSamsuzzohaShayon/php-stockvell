<?php
namespace Models\Member;
use Config\Database;




/**
 * @database operations
 */
class Member extends Database
{
    public function __construct($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source, $city)
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
        $this->govt_id = $govt_id;
        $this->source = $source;
        $this->city = $city;

        $this->ROOT = $_SERVER['DOCUMENT_ROOT'];
    }


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






    protected function updateMember($updateElement, $member_id)
    {
        $sql = "UPDATE members SET $updateElement WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        // $stmt->bindParam('updateElement', $updateElement,\PDO::PARAM_STR); // Not working
        try {
            if ($stmt->execute()) {
                header('Location: /dashboard.php?error=none');
            }
        } catch (\PDOException $e) {
            //throw $th;
            header('Location: /dashboard.php?error=stmtfailed');
        }
    }




    public function delete_prev_file_from_server($prev_name, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        if (file_exists($target_dir . $prev_name)) {
            unlink($target_dir . $prev_name);
        }
    }

    public function upload_file_to_server($uploadedFile, $ROOT, $is_member)
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


    public function updateDynamicMember($member_id)
    {
        if (!empty($this->email)) {
            $_SESSION["member_email"] = $this->email;
        }
        if (!empty($this->password)) {
            if ($this->password !== $this->password2) {
                header('Location: /dashboard.php?error=passwordnotmatch');
                exit();
            }
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password = $hashedPassword;
        }

        $unique_file_name = null;
        if ($this->govt_id['name']) {
            $file_sql = "SELECT * FROM members WHERE id=:member_id";
            $stmt = $this->connect()->prepare($file_sql);
            $stmt->bindParam('member_id', $member_id);
            $stmt->execute();
            $found_file = $stmt->fetch(\PDO::FETCH_OBJ);
            if ($found_file->govt_id) {
                $this->delete_prev_file_from_server($found_file->govt_id, $this->ROOT);
            }
            $unique_file_name = $this->upload_file_to_server($this->govt_id, $this->ROOT, true);
        }
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
            'govt_id' => $unique_file_name,
            'source' => $this->source,
            'city' => $this->city,
        );




        $cols = array();

        // Remove blank inputs and password2
        foreach ($this->input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }

        $updateElement = implode(', ', $cols);
        $this->updateMember($updateElement, $member_id);
    }
}







