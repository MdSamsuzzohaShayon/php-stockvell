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


$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}

$input_field = new InputField();

?>




<main class="signup">
  <section class="section-1">
    <div class="container">
      <div class="signup-caption text-center">
        <h1 class="h1"><?= __("Welcome to stockvel"); ?></h1>
        <p><?= __("Please enter the followings"); ?></p>
      </div>

      <?php if ($has_error) echo $err_handler->displayErrors(); ?>
      <!-- Signup Form start  -->
      <form action="/includes/signup.inc.php" method="POST" id="signup-form" >
        <div class="row mb-3">
          <?php
          $fn = __('Firstname*');
          $sn = __('Surname*');
          $el = __('Email*');
          $pw = __('Password*');
          $cpw = __('Confirm Password*');
          $cy = __('Country*');
          $cty = __('City*');
          $pn = __('Phone*');
          $gr = __('Select Gender*');
          $pro = __('Profession*');
          $ist = __("Interest (Optional comma-separated list)");
          $gid = __("Government ID*");
          $src = __('How did you hear about the Stockvel platform? (Optional)');
          $app = __("Agree on Privacy Policy");
          $h = __("Here");
          

          //echo inputElement("firstname", $fn, false, 'text', null, null, null, [], true);
          echo $input_field->inputText("firstname", $fn, false, "text", true);
          echo $input_field->inputText("surname", $sn, false, "text", true);
          ?>
        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputText("email", $el, true, "email", true);
          ?>
        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputText("password", $pw, false, "password", true);
          echo $input_field->inputText("password2", $cpw, false, "password", true);
          ?>

        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputSelect("country", $cy, false, "Benin", $country_list);
          echo $input_field->inputPhone("phone", $pn, false, true, $country_code_list, "+229");
          ?>
        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputText("city", $cty, false, "text", true);
          echo $input_field->inputSelect("gender", $gr, false, null, ["male", "female", "others"]);
          ?>
        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputSelect("profession", $pro, true, null, $professions);
          ?>
        </div>
        <div class="row mb-3">
          <?php
          echo $input_field->inputTextarea("interest", $ist, true);
          ?>
        </div>


        <div class="row mb-3">
          <?php
          echo $input_field->inputTextarea("source", $src, true);
          ?>
        </div>
        <div class="row mb-3 d-flex">
          <?php
            echo $input_field->inputCheckbox('pp', $app, false) . '<a href="/privacypolicy" class="w-fit">' . $h . '</a>';
          ?>
        </div>
        <div class="row row-no-input mb-3 d-flex justify-content-start">
          <button type="submit" name="member_signup_submit" class="btn btn-primary w-fit"><?= __("Signup"); ?></button>
          <a href="/" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
        </div>
        <div class="row row-no-input">
          <a class="p-0" href="/login"><?= __("Already have an account?"); ?></a>
        </div>
      </form>
      <!-- Signup Form end  -->
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>