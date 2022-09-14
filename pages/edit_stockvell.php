<?php
session_start(); // In every single page we should start our session at the top of our code


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



// If there is no member select redirect to admin page
if (!isset($_SESSION['admin_id']) || empty($_GET["stockvell_id"])) {
    header("Location: /admin");
    exit();
}




$admin_id = $_SESSION['admin_id'];
$stockvell_id = $_GET['stockvell_id'];
$logged_admin = false;
if (isset($admin_id)) $logged_admin = true;


use Utils\ErrorHandler;
use Utils\InputField;
use Models\Stockvell\FetchStockvell;


$fetch_stockvell = new FetchStockvell();

$fss_result = $fetch_stockvell->getSingleStockvellPack($stockvell_id); // fss = find single stockvell
if (!$fss_result) {
    header("Location: /admin/?error=stockvellnotfound");
    exit();
}
// echo json_encode($fss_result);
// exit();





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
            <h1 class="h1 text-center"><?= __("Edit any property of a Stockvell pack!"); ?>!</h1>
            <p class="text-center"><?= __("You can change any properties of the pack and republish the pack to members once again"); ?>!</p>

            <!-- Form start  -->
            <form action="/includes/edit_stockvell.inc.php" method="POST">
                <div class="row mb-3">
                    <?php // echo inputElement('name', 'Name*', false, 'text'); 
                    $nm = __("Name*");
                    $gl = __("Goal*");
                    $pyt = __("Payment*");
                    $pytf = __("Payment Frequency(days)*");
                    $wdf = __("Withdraw Frequency(days)*");
                    $ct = __("Category*");
                    $dsc = __("Description*");
                    $agmt = __("You Must Write Agreement About This Stockvell Pack*");
                    echo $input_field->inputText("name", $nm, false, "text", true, $fss_result->name);
                    echo $input_field->inputText("goal", $gl, false, "text", true, $fss_result->goal);
                    ?>

                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputTextarea("description", $dsc, true, true, $fss_result->description);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputText('payment', $pyt, false, 'number', true, $fss_result->payment);
                    echo $input_field->inputSelect("payment_frequency", $pytf, false, $fss_result->payment_frequency, $freq_days);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputSelect("category", $ct, false, $fss_result->category, ["social", "professional", "investmant"]);
                    echo $input_field->inputSelect("withdraw_frequency", $wdf, false, null, $freq_days);
                    ?>
                </div>
                <div class="row mb-3">
                    <?php
                    echo $input_field->inputTextarea("agreement", $agmt, true, true, $fss_result->agreement);
                    echo $input_field->inputHidden("stockvell_id", $stockvell_id);
                    ?>
                </div>


                <button type="submit" name="update_stockvell_pack" class="btn btn-primary"><?= __("Update Stockvell"); ?></button>
            </form>
            <!-- Form end  -->
            <!-- Update information end -->


        </div>
    </section>
</main>

<?php require_once("./layouts/footer.php"); ?>