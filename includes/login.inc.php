<?php

if(isset($_POST["submit"])){
  $email = $_POST["email"];
  $password = $_POST["password"];

  include "../config/database.php";
  include "../classes/login.classes.php";

  $login = new LoginController($email, $password);
  $login->loginMember();

  header("location: ../dashboard.php?error=none");
}

 ?>
