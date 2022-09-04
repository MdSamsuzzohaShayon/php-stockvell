<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
if (isset($member_email)) {
    header("Location: /dashboard.php");
    exit();
}

if (isset($_SESSION['admin_id'])) {
    header("Location: /admin.php");
    exit();
}


$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/classes/input.classes.php");
require_once($ROOT . "/utils/input-fields.php");

$error = $_GET["error"];
$err_handler = new ErrorHandler();
$err_arr = $err_handler->setCommonErrors($error);

?>



<main class="forget_password">
    <section class="section-1">
        <div class="container">
            <div class="login-caption text-center">
                <h1 class="h1"><?= __("Recover your account"); ?></h1>
                <p><?= __("Follow the process and do not reload the page during the process"); ?> </p>
            </div>

            <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>

            <!-- Form start here  -->
            <form class="d-none phone-form form-content" action="/includes/forget_password.inc.php" method="POST">
                <div class="row mb-3">
                    <?php echo inputElement('phone', 'Phone*', true, 'text'); ?>
                </div>
                <div class="row row-no-input mb-3">
                    <button type="submit" name="recover_via_phone_submit" class="btn btn-primary w-fit"><?= __("Search"); ?></button>
                    <a href="/index.php" class="btn btn-danger w-fit ms-3"><?= __("Cancel"); ?></a>
                </div>
                <div class="row row-no-input">
                    <a class="p-0" id="recover-via-email" href="#"><?= __("Use email address"); ?></a>
                </div>
            </form>
            <form class="d-block email-form form-content" action="/includes/forget_password.inc.php" method="POST">
                <div class="row mb-3">
                    <?php
                        echo inputElement('email', 'Email*', true, 'email');
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
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>