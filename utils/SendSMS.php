<?php

namespace Utils;


// relative vs. absolute path sometimes make a difference.
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

$dotenv = \Dotenv\Dotenv::createImmutable($ROOT);
$dotenv->safeLoad();

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

class SendSMS
{
    public function __construct()
    {
        // Your Account SID and Auth Token from twilio.com/console
        $this->sid = $_ENV['TWILIO_ACCOUNT_SID'];
        $this->token = $_ENV['TWILIO_ACCOUNT_TOKEN'];
        $this->client = new Client($this->sid, $this->token);
    }

    public function sendTwilioSMS($sendToNum, $smsBody)
    {
        try {

            //Recipients
            // Use the client to do fun stuff like send text messages!
            $this->client->messages->create(
                // the number you'd like to send the message to
                $sendToNum,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $_ENV['TWILIO_PHONE_NUMBER'],
                    // the body of the text message you'd like to send
                    'body' => $smsBody
                ]
            );
            // return true;
        } catch (\Exception $e) {
            echo "Message could not be sent. Error: " . $e->getMessage() . " <br />";
            // echo $e->getMessage();
        }
        return true;

    }
}








