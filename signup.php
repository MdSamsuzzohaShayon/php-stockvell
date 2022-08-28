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
require_once($ROOT . "/classes/input.classes.php"); // Error handler
require_once($ROOT . "/utils/input-fields.php");

$error = $_GET["error"];
$err_handler = new ErrorHandler();
$err_arr = $err_handler->setCommonErrors($error);
?>



<main class="signup">
  <section class="section-1">
    <div class="container">
      <div class="signup-caption text-center">
        <h1 class="h1">Welcome to stockvell</h1>
        <p>Please enter the followings</p>
      </div>

      <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
      <!-- Form start  -->
      <form action="/includes/signup.inc.php" method="POST">
        <div class="row mb-3">
          <?php echo inputElement("firstname", 'Firstname*', false, 'text', null, null, null, [], true); ?>
          <?php echo inputElement("surname", 'Surname*', false, 'text', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("email", 'Email*', true, 'email', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("password", 'Password*', false, 'password', null, null, null, [], true); ?>
          <?php echo inputElement("password2", 'Confirm Password*', false, 'password', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("country", 'Country*', false, 'select', null, null, null, $countries); ?>
          <?php echo inputElement("phone", 'Phone*', false, 'number', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("gender", 'Select Gender*', false, 'select', null, null, null, ["male", "female", "others"]); ?>
          <?php echo inputElement("profession", 'Profession*', false, 'select', null, null, null, $professions); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("interest", 'Interest (Optional comma seperated list)', true, 'text', null, null, null, [], true); ?>
          <?php echo inputElement("govt_id", 'Government ID*', false, 'file', null, null, null, [], true); ?>
        </div>


        <div class="row mb-3">
          <?php echo inputElement("source", 'How do you hear about the Stockvell platform? (Optional)', true, 'textarea', null, null, null, [], true); ?>
        </div>

        <button type="submit" name="member_signup_submit" class="btn btn-primary">Signup</button>
      </form>
      <!-- Form end  -->
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>