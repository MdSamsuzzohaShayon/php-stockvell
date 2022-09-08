<?php 
namespace Models\Member;
use Config\Database;

class MemberForms extends Database
{
    public function verifyByAdmin($member_id)
    {
        $sql = "UPDATE members SET is_verified=:is_verified WHERE id=$member_id";
        $stmt = $this->connect()->prepare($sql);
        $is_verified = 1;
        $stmt->bindParam('is_verified', $is_verified);
        if($stmt->execute()){
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