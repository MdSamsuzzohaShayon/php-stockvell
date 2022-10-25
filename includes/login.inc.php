<?php

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");
use Models\Member\Login;
use Models\Admin\AdminLogin;


if(isset($_POST["member_email_login"])){
  $email = $_POST["email"];
  $password = $_POST["password"];
  // echo json_encode(array("email"=> $email, "password" => $password));
  // exit();



  $login = new Login();
  $login->setEmailPassword($email, $password);
  $login->loginMember();

  header("location: /dashboard");
}

if(isset($_POST["member_phone_login"])){
  $phone = trim($_POST["phone"]);
  $password = trim($_POST["password"]);

  // include $ROOT . "/config/Database.php";
  // include $ROOT . "/Models/Login.php";

  $login = new Login();
  $login->setPhonePassword($phone, $password);
  $login->memberLoginViaPhone();

  header("location: /dashboard");
}





 ?>
