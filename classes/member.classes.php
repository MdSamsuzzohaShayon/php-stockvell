<?php

interface FileHandler
{
    public function delete_prev_file_from_server($prev_name, $ROOT);
    public function upload_file_to_server($uploadedFile, $ROOT);
}


class Login extends Database
{

    protected function getMember($password, $email)
    {
        // select * from members where email='mdshayon0@gmail.com'
        $stmt = $this->connect()->prepare("SELECT * FROM members WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /login.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("Location: /login.php?error=usernotfound");
            exit();
        }


        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPassword = password_verify($password, $members[0]["password"]);

        if ($checkPassword  == false) {
            $stmt = null;
            header("Location: /login.php?error=incorrectpassword");
            exit();
        } elseif ($checkPassword == true) {
            // set cookie
            session_start();
            $_SESSION["member_id"] = $members[0]["id"];
            $_SESSION["member_role"] = $members[0]["role"];
            $_SESSION["member_username"] = $members[0]["firstname"] . " " . $members[0]["surname"];
            $_SESSION["member_email"] = $members[0]["email"];
            $stmt = null;
            header("Location: /dashboard.php");
        }

        $stmt = null;
    }

    protected function getAdmin($email, $password)
    {
        // select * from members where email='mdshayon0@gmail.com'
        $stmt = $this->connect()->prepare("SELECT * FROM admins WHERE email = :email ");

        if (!$stmt->execute(array(':email' => $email))) {
            $stmt = null;
            header("Location: /admin.php?error=stmtfailed");
            exit();
        }


        $admin = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$admin) {
            $stmt = null;
            header("Location: /admin.php?error=usernotfound");
            exit();
        }
        $checkPassword = password_verify($password, $admin->password);

        if ($checkPassword  == false) {
            $stmt = null;
            header("Location: /admin.php?error=incorrectpassword");
            exit();
        } elseif ($checkPassword == true) {
            // set cookie
            session_start();
            $_SESSION["admin_id"] = $admin->id;
            $_SESSION["admin_role"] = $admin->role;
            $_SESSION["admin_username"] = $admin->name;
            $_SESSION["admin_email"] = $admin->email;
            $stmt = null;
            header("Location: /admin.php?error=none");
            exit();
        }

        $stmt = null;
    }
}



class LoginController extends Login
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function loginMember()
    {
        if (empty($this->email) || empty($this->password)) {
            header("Location: /login.php?error=emptyinput");
            exit();
        }
        $this->getMember($this->password, $this->email);
    }

    public function loginAdmin()
    {
        if (empty($this->email) || empty($this->password)) {
            header("Location: /admin.php?error=emptyinput");
            exit();
        }
        $this->getAdmin($this->email, $this->password);
    }
}



/**
 * @database operations
 */
class Signup extends Database
{

    protected function setMember($firstname, $surname, $email, $password, $country, $phone, $gender, $profession, $interest, $govt_id, $source, $city)
    {

        $stmt = $this->connect()->prepare('INSERT INTO members(firstname, surname, email, password,  country, phone, gender, profession, interest, govt_id, source, role, city) VALUES (:firstname, :surname, :email, :password,  :country, :phone, :gender, :profession, :interest, :govt_id, :source, :role, :city);');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam('firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam('surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        $stmt->bindParam('password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam('country', $country, PDO::PARAM_STR);
        $stmt->bindParam('city', $city, PDO::PARAM_STR);
        $stmt->bindParam('phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam('gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam('profession', $profession, PDO::PARAM_STR);
        $stmt->bindParam('interest', $interest, PDO::PARAM_STR);
        $stmt->bindParam('govt_id', $govt_id, PDO::PARAM_STR);
        $stmt->bindParam('source', $source, PDO::PARAM_STR);
        $role = "GENERAL";
        $stmt->bindParam('role', $role, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: /signup.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }


    protected function memberExist($email)
    {
        $stmt = $this->connect()->prepare("SELECT * FROM members WHERE email = :email");

        // select * from members where email='mdshayon0@gmail.com' or username='shayon';
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: /signup.php?error=stmtfailed");
            exit();
        }
        $resultCheck = null;
        if ($stmt->rowCount() > 0) {
            $resultCheck = true;
        } else {
            $resultCheck = false;
        }
        return $resultCheck;
    }
}





/**
 * @validate all inputs 
 */
class SignupController extends Signup implements FileHandler
{
    private $firstname;
    private $surname;
    private $email;
    private $password;
    private $password2;
    private $country;
    private $phone;
    private $gender;
    private $profession;
    private $interest;
    private $govt_id;
    private $source;
    private $city;
    // private $role;

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


    public function delete_prev_file_from_server($prev_name, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        if (file_exists($target_dir . $prev_name)) {
            unlink($target_dir . $prev_name);
        }
    }

    public function upload_file_to_server($uploadedFile, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $uploadedFile["name"]);
        $target_file = $target_dir . basename($unique_file_name . $uploadedFile["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // 1kb = 1000, 1 mb = 1000 kb
        if ($uploadedFile['size'] > (1000 * 1000 * 2)) {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        // Upload file
        move_uploaded_file($uploadedFile["tmp_name"], $target_dir . $unique_file_name);
        return $unique_file_name;
    }


    public function signupMember()
    {
        // echo $this->govt_id["size"];
        // exit();
        if (empty($this->firstname) || empty($this->email) || empty($this->password) || empty($this->password2) || empty($this->surname) || empty($this->country) || empty($this->phone) || empty($this->gender) || empty($this->profession) || empty($this->interest)  || empty($this->source)) {
            header("Location: /signup.php?error=emptyinput");
            exit();
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->username) || strlen($this->firstname) <= 1) {
            header("Location: /signup.php?error=invalidusername");
            exit();
        }
        if (!preg_match("/^[0-9]*$/", $this->phone)) {
            header("Location: /signup.php?error=invalidphone");
            exit();
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            header("Location: /signup.php?error=invalidemail");
            exit();
        }
        if ($this->password !== $this->password2) {
            header("Location: /signup.php?error=passwordnotmatch");
            exit();
        }
        if ($this->memberExist($this->email) == true) {
            header("Location: /signup.php?error=alreadyexist");
            exit();
        }

        if ($this->memberExist($this->email) == true) {
            header("Location: /signup.php?error=alreadyexist");
            exit();
        }



        /*
        // File inputs
        $target_dir = $this->ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $this->govt_id["name"]);
        $target_file = $target_dir . basename($unique_file_name . $this->govt_id["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // 1kb = 1000, 1 mb = 1000 kb
        if ($this->govt_id['size'] > (1000 * 1000 * 2)) {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        // if (file_exists($target_dir . $unique_file_name)) {
        //     header("Location: /signup.php?error=invalidfile");
        //     exit();
        // }


        // Upload file
        move_uploaded_file($this->govt_id["tmp_name"], $target_dir . $unique_file_name);
        // move_uploaded_file($this->govt_id["tmp_name"], "uploads/" . $this->govt_id["name"]);
        */

        $unique_file_name = $this->upload_file_to_server($this->govt_id, $this->ROOT);
        $this->setMember($this->firstname, $this->surname, $this->email, $this->password, $this->country, $this->phone, $this->gender, $this->profession, $this->interest, $unique_file_name, $this->source, $this->city);
    }
}






/**
 * @database operations
 */
class Member extends Database
{
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


        $member_found = $stmt->fetch(PDO::FETCH_OBJ);
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
        $member_found = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$member_found) {
            $stmt = null;
            header("Location: /$redirect_url?error=usernotfound");
            exit();
        }
        return $member_found;
    }



    protected function updateMember($updateElement, $member_id)
    {
        $sql = "UPDATE members SET $updateElement WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        // $stmt->bindParam('updateElement', $updateElement,PDO::PARAM_STR); // Not working
        try {
            if ($stmt->execute()) {
                header('Location: /dashboard.php?error=none');
            }
        } catch (PDOException $e) {
            //throw $th;
            header('Location: /dashboard.php?error=stmtfailed');
        }
    }
}


/**
 * @validate all inputs 
 */
class MemberController extends Member implements FileHandler
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

    public function delete_prev_file_from_server($prev_name, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        if (file_exists($target_dir . $prev_name)) {
            unlink($target_dir . $prev_name);
        }
    }

    public function upload_file_to_server($uploadedFile, $ROOT)
    {
        $target_dir = $ROOT . "/uploads/";
        $unique_file_name = basename("m_" . date("Ymd_") . $uploadedFile["name"]);
        $target_file = $target_dir . basename($unique_file_name . $uploadedFile["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // 1kb = 1000, 1 mb = 1000 kb
        if ($uploadedFile['size'] > (1000 * 1000 * 2)) {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
            header("Location: /signup.php?error=invalidfile");
            exit();
        }
        // Upload file
        move_uploaded_file($uploadedFile["tmp_name"], $target_dir . $unique_file_name);
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
            $found_file = $stmt->fetch(PDO::FETCH_OBJ);
            if ($found_file->govt_id) {
                $this->delete_prev_file_from_server($found_file->govt_id, $this->ROOT);
            }
            $unique_file_name = $this->upload_file_to_server($this->govt_id, $this->ROOT);
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


/**
 * @all type of forms
 */

class MemberForms extends Member
{
    public function verifyByAdmin($member_id)
    {
        $sql = "UPDATE members SET is_verified=:is_verified WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        $is_verified = 1;
        $stmt->bindParam('is_verified', $is_verified);
        $stmt->execute();

        header('Location: /admin.php');
    }




    public function generateBackupCodeViaEmail($email)
    {
        $fmber_result = $this->findMemberByEmail($email, 'forget_password.php'); // fmber = found member by email result
        $recovery_code = mt_rand(1, 999999);
        // Save to database
        $member_update_sql = "UPDATE members SET recovery_code=:recovery_code WHERE id=:member_id";
        $rcu_stmt = $this->connect()->prepare($member_update_sql); // rcu = recovery code statement
        $rcu_stmt->bindParam('member_id', $fmber_result->id);
        $rcu_stmt->bindParam('recovery_code', $recovery_code);
        $rcu_stmt->execute();
        $this->sendBackupCodeThoughEmail($recovery_code);
    }

    public function generateBackupCodeViaPhone($phone)
    {
        $recovery_code = mt_rand(1, 999999);
        // Save to database
        $member_update_sql = "UPDATE members SET recovery_code=:recovery_code WHERE phone=:phone";
        $rcu_stmt = $this->connect()->prepare($member_update_sql); // rcu = recovery code statement
        $rcu_stmt->bindParam('phone', $phone);
        $rcu_stmt->bindParam('recovery_code', $recovery_code);
        $rcu_stmt->execute();
        $this->sendBackupCodeThoughPhone($recovery_code);
    }

    private function sendBackupCodeThoughEmail($recovery_code)
    {
        $to      = 'mdsamsuzzoha5222@gmail.com';
        $subject = 'the subject';
        $message = "hello - $recovery_code";
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }

    private function sendBackupCodeThoughPhone($recovery_code)
    {
    }

    public function verifyBackupCode()
    {
        $recovery_code = mt_rand(1, 999999);
        // Save to database
    }
}
