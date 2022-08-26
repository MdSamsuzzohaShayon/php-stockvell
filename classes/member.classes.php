<?php

/**
 * @database operations
 */
class Member extends Database
{
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
class MemberController extends Member
{

    public function __construct($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source)
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
    }


    public function updateDynamicMember($member_id)
    {
        if (!empty($this->password)) {
            if ($this->password !== $this->password2) {
                header('Location: /dashboard.php?error=passwordnotmatch');
                exit();
            }
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $this->password = $hashedPassword;
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
            'govt_id' => $this->govt_id,
            'source' => $this->source,
        );

        $cols = array();

        foreach ($this->input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'"; // Remove blank inputs and password2
        }

        $updateElement = implode(', ', $cols);
        $this->updateMember($updateElement, $member_id);
    }
}
