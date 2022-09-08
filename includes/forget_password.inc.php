<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once($ROOT . "/vendor/autoload.php");
use Models\Member\MemberForms;


if(isset($_POST["recover_via_email_submit"])){
  $email = $_POST["email"];
  $member_recover = new MemberForms();
  $member_recover->generateBackupCodeViaEmail($email);
  // header("location: /dashboard.php");
}

if(isset($_POST["recover_via_phone_submit"])){
  $phone = $_POST["phone"];
  $member_recover = new MemberForms();
  $member_recover->generateBackupCodeViaPhone($phone);
  // header("location: /dashboard.php");
}

 ?>
