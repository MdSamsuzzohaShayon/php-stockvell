<?php
session_start(); // In every single page we should start our session at the top of our code
require_once($ROOT . "/includes/edit_member.inc.php");


$ROOT = $_SERVER['DOCUMENT_ROOT'];
// Check for session 
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/includes/edit_member.inc.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");





// member can not access this page
if (isset($_SESSION['member_id'])) {
    header("Location: /dashboard.php");
    exit();
}



// If there is no member select redirec to admin page
if (!isset($_SESSION['admin_id']) || empty($_GET["member_id"])) {
    header("Location: /admin");
    exit();
}




$admin_id = $_SESSION['admin_id'];
$member_id = $_GET['member_id'];
$logged_admin = false;
if (isset($admin_id)) $logged_admin = true;


use Utils\ErrorHandler;
use Utils\InputField;
use Models\Member\FetchMember;


$fetch_member = new FetchMember();

$fmbi_result = $fetch_member->findMemberByID($member_id, "admin.php");
if (!$fmbi_result) {
    header("Location: /admin/?error=usernotfound");
    exit();
}





$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}




$input_field = new InputField();
?>



<main class="admin">
    <section class="section-1">
        <div class="container">
            <?php if ($has_error) echo $err_handler->displayErrors(); ?>

            <!-- Update information start  -->
            <div class="signup-caption text-center">
                <h1 class="h1"><?= __("Update member informations!") ?></h1>
                <p><?= __("You can change any field") ?></p>
            </div>
            <!-- Signup Form start  -->
            <form action="/includes/edit_member.inc.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <?php
                    $fn = __('Firstname');
                    $sn = __('Surname');
                    $el = __('Email');
                    $pw = __('Password');
                    $cpw = __('Confirm Password');
                    $cy = __('Country');
                    $cty = __('City');
                    $pn = __('Phone');
                    $gr = __('Select Gender');
                    $pro = __('Profession');
                    $ist = __("Interest (Optional comma-separated list)");
                    $gid = __("Government ID");
                    $src = __('How did you hear about the Stockvell platform? (Optional)');

                    echo $input_field->inputText("firstname", $fn, false, "text", false, $fmbi_result->firstname);
                    echo $input_field->inputText("surname", $sn, false, "text", false, $fmbi_result->surname);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputText("email", $el, true, "email", false, $fmbi_result->email);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputText("password", $pw, false, "password", false);
                    echo $input_field->inputText("password2", $pw, false, "password", false);
                    ?>

                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputSelect("country", $cy, false, $fmbi_result->country, $countries_code);
                    // echo $input_field->inputPhone("phone", $pn, false, false, $fmbi_result->phone);

                    echo $input_field->inputPhone("phone", $pn, false, true, $phone_code, $fmbi_result->phone);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputText("city", $cty, false, "text", false, $fmbi_result->city);
                    echo $input_field->inputSelect("gender", $gr, false, $fmbi_result->gender, ["male", "female", "others"]);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputSelect("profession", $pro, false, $fmbi_result->profession, $professions);
                    echo $input_field->inputFile("govt_id", $gid, false);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputTextarea("interest", $ist, true, false, $fmbi_result->interest);
                    ?>
                </div>


                <div class="row mb-3">
                    <?php
                    echo $input_field->inputTextarea("source", $src, true, false, $fmbi_result->source);
                    echo $input_field->inputHidden("member_id", $member_id);
                    ?>
                </div>

                <button type="submit" name="member_update_submit" class="btn btn-primary"><?= __("Update"); ?></button>
            </form>
            <!-- Signup Form end  -->
            <!-- Update information end -->


        </div>
    </section>
</main>

<?php require_once("./layouts/footer.php"); ?>