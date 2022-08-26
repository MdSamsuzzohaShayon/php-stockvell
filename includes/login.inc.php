<?php

if(isset($_POST["submit"])){
  $ROOT = $_SERVER['DOCUMENT_ROOT'];
  $email = $_POST["email"];
  $password = $_POST["password"];

  include $ROOT . "/config/database.php";
  include $ROOT . "/classes/login.classes.php";

  $login = new LoginController($email, $password);
  $login->loginMember();

  header("location: /dashboard.php");
}

 ?>
