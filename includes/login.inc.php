<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];

if(isset($_POST["member_login_submit"])){
  $email = $_POST["email"];
  $password = $_POST["password"];

  include $ROOT . "/config/database.php";
  include $ROOT . "/classes/login.classes.php";

  $login = new LoginController($email, $password);
  $login->loginMember();

  header("location: /dashboard.php");
}

if(isset($_POST["login_admin_submit"])){
  $admin_email = $_POST["email"];
  $admin_password = $_POST["password"];

  include $ROOT . "/config/database.php";
  include $ROOT . "/classes/login.classes.php";

  $login = new LoginController($admin_email , $admin_password);
  $login->loginAdmin();

  header("location: /dashboard.php");
}

 ?>
