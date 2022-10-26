<?php

namespace Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// relative vs. absolute path sometimes make a difference.
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

$dotenv = \Dotenv\Dotenv::createImmutable($ROOT);
$dotenv->safeLoad();

class SendEmail
{
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = $_ENV["EMAIL_SMTP_HOST"];                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = $_ENV["EMAIL_ADMIN"];                     //SMTP username
        $this->mail->Password   = $_ENV["EMAIL_PASSWORD"];                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $this->mail->Port       = intval($_ENV["EMAIL_SMTP_PORT"]);                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    }

    public function sendMessage($sendTo, $htmlBody, $subject)
    {
        try {

            //Recipients
            $this->mail->setFrom($_ENV["EMAIL_ADMIN"], 'Stockvell');
            $this->mail->addAddress($sendTo, 'Md Shayon');     //Add a recipient
            // $this->mail->addAddress('ellen@example.com');               //Name is optional
            // $this->mail->addReplyTo('admin@thesportsanctum.com', 'Information');
            // $this->mail->addCC('cc@example.com');
            // $this->mail->addBCC('bcc@example.com');

            //Attachments
            // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $htmlBody;
            // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->mail->send();
            // echo 'Message has been sent';
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo} <br />";
            echo $e->getMessage();
            exit();
        }
        return false;
    }
}
