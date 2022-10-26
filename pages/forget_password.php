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

$segment = $_GET["segment"];

$input_field = new InputField();

?>



<main class="forget_password">
    <section class="section-1">
        <div class="container">
            <div class="login-caption text-center">
                <h1 class="h1"><?= __("Recover your account"); ?></h1>
                <p><?= __("Follow the process and do not reload the page during the process"); ?> </p>
            </div>

            <?php if ($has_error) echo $err_handler->displayErrors(); ?>

            <?php if ($segment === "verify_code") { ?>
                <form class="verify-code" action="/includes/forget_password.inc.php" method="POST">
                    <div class="row mb-3">
                        <?php
                        $rc = "Recovery Code*";
                        echo $input_field->inputText("recovery_code", $rc, true, 'number', true);
                        ?>
                    </div>
                    <div class="row row-no-input mb-3">
                        <button type="submit" name="recover_code_submit" class="btn btn-primary w-fit"><?= __("Verify"); ?></button>
                        <a href="/login" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
                    </div>
                </form>
            <?php } elseif ($segment === "reset_password") { ?>
                <!-- Form start here  -->
                <!-- Validate the user -->
                <?php
                if (isset($_GET['user_id'])) { ?>
                    <form class="d-block email-form form-content" action="/includes/forget_password.inc.php" method="POST">
                        <div class="row mb-3">
                            <?php
                            $pw = __("Password*");
                            $pw2 = __("Confirm Password*");
                            echo $input_field->inputHidden("id", $_GET['user_id']);
                            echo $input_field->inputText("password", $pw, false, "password", true);
                            echo $input_field->inputText("password2", $pw2, false, "password", true);
                            ?>
                        </div>
                        <div class="row row-no-input mb-3">
                            <button type="submit" name="reset_password_submit" class="btn btn-primary w-fit"><?= __("Reset Password"); ?></button>
                            <a href="/index.php" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
                        </div>
                    </form>
                <?php } ?>

                <!-- Form ends here  -->
            <?php } else { ?>
                <!-- Recover by email start -->
                <form class="d-none phone-form form-content" action="/includes/forget_password.inc.php" method="POST">
                    <div class="row mb-3">
                        <?php
                        $pn = "Phone*";
                        // echo $input_field->inputPhone("phone", $pn, true, true);
                        echo $input_field->inputPhone("phone", $pn, true, true, $phone_code, "+229");
                        ?>
                    </div>
                    <div class="row row-no-input mb-3">
                        <button type="submit" name="recover_via_phone_submit" class="btn btn-primary w-fit"><?= __("Search"); ?></button>
                        <a href="/index.php" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
                    </div>
                    <div class="row row-no-input">
                        <a class="p-0" id="recover-via-email" href="#"><?= __("Use email address"); ?></a>
                    </div>
                </form>
                <!-- Recover by email end -->


                <!-- Recover by phone start  -->
                <form class="d-block email-form form-content" action="/includes/forget_password.inc.php" method="POST">
                    <div class="row mb-3">
                        <?php
                        echo $input_field->inputText("email", "Email*", true, "email");
                        ?>
                    </div>
                    <div class="row row-no-input mb-3">
                        <button type="submit" name="recover_via_email_submit" class="btn btn-primary w-fit"><?= __("Search"); ?></button>
                        <a href="/index.php" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
                    </div>
                    <div class="row row-no-input">
                        <a class="p-0" id="recover-via-phone" href="#"><?= __("Use phone number"); ?></a>
                    </div>
                </form>
                <!-- Recover by phone end  -->
            <?php } ?>

        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>