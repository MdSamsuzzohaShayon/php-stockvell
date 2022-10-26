<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];

require_once($ROOT . "/vendor/autoload.php");

use Models\Member\RecoverPassword;


if (isset($_POST["recover_via_email_submit"])) {
  $email = $_POST["email"];
  $member_recover = new RecoverPassword();
  $member_recover->generateBackupCodeViaEmail($email);
  header("location: /forget_password/?segment=verify");
  exit();
}

if (isset($_POST["recover_via_phone_submit"])) {
  $phone = $_POST["phone"];

  $member_recover = new RecoverPassword();
  $member_recover->generateBackupCodeViaPhone($phone);
}


if (isset($_POST["recover_code_submit"])) {
  $recovery_code = $_POST["recovery_code"];
  $member_recover = new RecoverPassword();
  $member_recover->verifyRecoveryCode($recovery_code);
  // header("Location: /forget_password/?segment=verify_code&recovery_code=$recovery_code");
  // exit();
}

if(isset($_POST["reset_password_submit"])){
  $user_id = $_POST["id"];
  $password = $_POST["password"];
  $password2 = $_POST["password2"];

  $member_recover = new RecoverPassword();
  $member_recover->resetNewPassword($user_id, $password, $password2);
}
