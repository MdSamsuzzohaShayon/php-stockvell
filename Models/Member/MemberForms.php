<?php

namespace Models\Member;

use Config\Database;
use Utils\SendEmail;

class MemberForms extends Database
{
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

}
