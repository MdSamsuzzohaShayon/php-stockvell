<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
if (isset($member_email)) {
  header("Location: /dashboard.php");
  exit();
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
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
        <h1 class="h1"><?= __("Welcome to stockvell"); ?></h1>
        <p><?= __("Please enter the followings" ); ?></p>
      </div>

      <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
      <!-- Form start  -->
      <form action="/includes/signup.inc.php" method="POST" enctype="multipart/form-data">
        <div class="row mb-3">
          <?php
          $fn = __('Firstname*');
          $sn = __('Surname*');
          $el= __('Email*');
          $pw= __('Password*');
          $cpw= __('Confirm Password*');
          $cy= __('Country*');
          $pn= __('Phone*');
          $gr= __('Select Gender*');
          $pro= __('Profession*');
          $ist= __("Interest (Optional comma-separated list)");
          $gid= __("Government ID*");
          $src= __('How did you hear about the Stockvell platform? (Optional)');

          echo inputElement("firstname", $fn, false, 'text', null, null, null, [], true);
          ?>
          <?php echo inputElement("surname", $sn, false, 'text', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("email", $el, true, 'email', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("password", $pw, false, 'password', null, null, null, [], true); ?>
          <?php echo inputElement("password2", $cpw, false, 'password', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("country", $cy, false, 'select', null, null, null, $countries); ?>
          <?php echo inputElement("phone", $pn, false, 'number', null, null, null, [], true); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("gender", $gr, false, 'select', null, null, null, ["male", "female", "others"]); ?>
          <?php echo inputElement("profession", $pro, false, 'select', null, null, null, $professions); ?>
        </div>
        <div class="row mb-3">
          <?php echo inputElement("interest", $ist, true, 'text', null, null, null, [], true); ?>
          <?php echo inputElement("govt_id", $gid, false, 'file', null, null, null, [], true); ?>
        </div>


        <div class="row mb-3">
          <?php echo inputElement("source", $src, true, 'textarea', null, null, null, [], true); ?>
        </div>

        <button type="submit" name="member_signup_submit" class="btn btn-primary"><?= __("Signup"); ?></button>
      </form>
      <!-- Form end  -->
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>