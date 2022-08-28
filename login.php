<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
if (isset($member_email)) {
  header("Location: /dashboard.php");
  exit();
}




$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");
require_once($ROOT . "/classes/input.classes.php");
require_once($ROOT . "/utils/input-fields.php");

$error = $_GET["error"];
$err_handler = new ErrorHandler();
$err_arr = $err_handler->setCommonErrors($error);

?>



<main class="login">
  <section class="section-1">
    <div class="container">
      <div class="login-caption text-center">
        <h1 class="h1">Welcome to stockvell</h1>
        <p>Please enter the followings to login as member</p>
      </div>

      <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>

      <!-- Form start here  -->
      <form action="/includes/login.inc.php" method="POST">
        <div class="row mb-3">
          <?php echo inputElement('email', 'Email*', false, 'email'); ?>
          <?php echo inputElement('password', 'Password', false, 'password'); ?>
        </div>
        <button type="submit" name="member_login_submit" class="btn btn-primary">login</button>
      </form>
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>