<?php

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");
use Models\Member\Login;
use Models\Admin\AdminLogin;


if(isset($_POST["member_login_submit"])){
  $email = $_POST["email"];
  $password = $_POST["password"];

  // include $ROOT . "/config/Database.php";
  // include $ROOT . "/Models/Login.php";

  $login = new Login($email, $password);
  $login->loginMember();

  header("location: /dashboard");
}

if(isset($_POST["login_admin_submit"])){
  $admin_email = $_POST["email"];
  $admin_password = $_POST["password"];

  // include $ROOT . "/config/Database.php";
  // include $ROOT . "/Models/Login.php";

  $login = new AdminLogin($admin_email , $admin_password);
  $login->loginAdmin();

  header("location: /dashboard");
}

 ?>
