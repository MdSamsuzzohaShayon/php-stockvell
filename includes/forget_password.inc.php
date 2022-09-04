<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];

include $ROOT . "/config/database.php";
include $ROOT . "/classes/member.classes.php";


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
