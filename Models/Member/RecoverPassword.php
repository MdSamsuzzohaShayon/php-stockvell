<?php

namespace Models\Member;

// Delete this file in production
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");


use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

use Utils\SendEmail;
use Models\Member\Member;
// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

class RecoverPassword extends Member
{
    public function generateBackupCodeViaEmail($email)
    {
        $fmber_result = $this->findMemberByEmail($email, 'forget_password.php'); // fmber = found member by email result
        if (!$fmber_result) {
            header("Location: /forget_password.php?error=usernotfound");
            exit();
        }
        // $random_code = rand(100000, 999999);
        $random_code_str = strval(rand(1000, 9999));
        $id_str = strval($fmber_result->id);
        $recovery_code = $random_code_str . $id_str;
        // $token = $this->generateEncodedToken(array("email"=> "mdshayon0@gmail.com"), "secret");
        // $this->decodeToken($token);
        // Save to database
        $member_update_sql = "UPDATE members SET recovery_code=:recovery_code WHERE id=:member_id";
        $rcu_stmt = $this->connect()->prepare($member_update_sql); // rcu = recovery code statement
        $rcu_stmt->bindParam('member_id', $fmber_result->id);
        $rcu_stmt->bindParam('recovery_code', $recovery_code);
        $rcu_stmt->execute();
        $this->sendBackupCodeThoughEmail($recovery_code, $email);

        header("Location: /forget_password/?segment=verify_code");
        exit();
    }

    public function generateBackupCodeViaPhone($phone)
    {
        // $recovery_code = mt_rand(100000, 999999);
        // $recovery_code = rand(100000, 999999);
        $fmber_result = $this->findMemberByPhone($phone, 'forget_password.php'); // fmber = found member by email result
        if (!$fmber_result) {
            header("Location: /forget_password.php?error=usernotfound");
            exit();
        }
        // $random_code = rand(100000, 999999);
        $random_code_str = strval(rand(1000, 9999));
        $id_str = strval($fmber_result->id);
        $recovery_code = $random_code_str . $id_str;
        // Save to database
        $member_update_sql = "UPDATE members SET recovery_code=:recovery_code WHERE phone=:phone";
        $rcu_stmt = $this->connect()->prepare($member_update_sql); // rcu = recovery code statement
        $rcu_stmt->bindParam('phone', $phone);
        $rcu_stmt->bindParam('recovery_code', $recovery_code);
        $rcu_stmt->execute();
        $this->sendBackupCodeThoughPhone($recovery_code, $phone);
        header("Location: /forget_password/?segment=verify_code");
        exit();
    }

    private function sendBackupCodeThoughEmail($recovery_code, $email)
    {
        $send_email = new SendEmail();
        $html_msg = "
            <h1>You stockvell code is here</h1>
            <p style='color:white;font-size: 2rem; padding:10px 3rem; background:#808080;'>$recovery_code</p>
            <p>Use this code to reset your password. This code is valid for certain period of time</p>
        ";
        $send_email->sendMessage($email, $html_msg, 'Password Recovery Code');
        // Redirect

    }

    private function sendBackupCodeThoughPhone($recovery_code, $phone)
    {
        $formatted_phone = str_replace("_", "", $phone);
        // Send recovery code via twilio
        $msg = "Please use this $recovery_code recovery code to verify your account";
        // Your Account SID and Auth Token from twilio.com/console

        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        $sid = $_ENV["TWILIO_ACCOUNT_SID"];
        $token = $_ENV["TWILIO_ACCOUNT_TOKEN"];
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                $formatted_phone, // to
                ["from" => $_ENV["TWILIO_PHONE_NUMBER"], "body" => $msg]
            );

        print($message->sid);
    }

    public function verifyRecoveryCode($recovery_code)
    {
        // echo $recovery_code;
        // exit();
        $sql = "SELECT id, firstname, email FROM members WHERE recovery_code=:recovery_code";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam("recovery_code", $recovery_code);
        $stmt->execute();
        $fmbrc_result = $stmt->fetch(\PDO::FETCH_OBJ); // fmbrc = find member by recovery code
        if ($fmbrc_result->id) {
            // proceed
            $user_id = $fmbrc_result->id;
            // var_dump($fmbrc_result);
            header("Location: /forget_password/?segment=reset_password&user_id=$user_id");
            exit();
        } else {
            header("Location: /forget_password/?error=invalidcode");
            exit();
        }

        exit();
    }

    public function resetNewPassword($user_id, $password, $password2)
    {
        if (empty($password) || empty($password2)) {
            header("Location: /forget_password/?segment=reset_password&user_id=$user_id&error=passwordnotmatch");
            exit();
        }

        if ($password !== $password2) {
            header("Location: /forget_password/?segment=reset_password&user_id=$user_id&error=passwordnotmatch");
            exit();
        }

        //  Update member 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update_arr = [" password= '$hashedPassword' "];
        $this->updateMember($update_arr, $user_id, "/login.php");
    }
}
