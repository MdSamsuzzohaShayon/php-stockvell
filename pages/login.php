<?php
session_start(); // In every single page we should start our session at the top of our code
if (isset($_SESSION['member_email'])) {
  header("Location: /dashboard.php");
  exit();
}

if (isset($_SESSION['admin_id'])) {
  header("Location: /admin.php");
  exit();
}


$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");
// require_once($ROOT . "/Models/input.classes.php");
// require_once($ROOT . "/utils/input-fields.php");

use Utils\ErrorHandler;
use Utils\InputField;

$err_arr = [];
$err_handler = new ErrorHandler();
if(isset($_GET["error"])){
  $err_arr = $err_handler->setCommonErrors($_GET["error"]);
}

$input_field = new InputField();

?>



<main class="login">
  <section class="section-1">
    <div class="container">
      <div class="login-caption text-center">
        <h1 class="h1"><?= __("Welcome to stockvell"); ?></h1>
        <p><?= __("Please enter the following to login as a member" ); ?> </p>
      </div>

      <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>

      <!-- Form start here  -->
      <form action="/includes/login.inc.php" method="POST">
        <div class="row mb-3">
          <?php // echo inputElement('email', 'Email*', false, 'email'); 
          echo $input_field->inputText("email", "Email", false, "email", true);
          ?>
          <?php 
          $password = __("Password");
          // echo inputElement('password', $password, false, 'password'); 
          echo $input_field->inputText("password", $password, false, "password", true);
          ?>
        </div>
        <div class="row row-no-input mb-3">
          <button type="submit" name="member_login_submit" class="btn btn-primary w-fit"><?= __("Login"); ?></button>
          <a href="/index" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
        </div>
        <div class="row row-no-input">
          <a class="p-0" href="/forget_password"><?= __("Forgot Password?"); ?></a>
          <a class="p-0" href="/signup"><?= __("Do not have an account?"); ?></a>
        </div>
      </form>
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>