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
if (!isset($_SESSION['admin_id']) || empty($_GET["stockvel_id"])) {
    header("Location: /admin");
    exit();
}




$admin_id = $_SESSION['admin_id'];
$stockvel_id = $_GET['stockvel_id'];
$logged_admin = false;
if (isset($admin_id)) $logged_admin = true;


use Utils\ErrorHandler;
use Utils\InputField;
use Models\Stockvell\FetchStockvell;
use Utils\PeriodConvert;


$fetch_stockvell = new FetchStockvell();

$fss_result = $fetch_stockvell->getSingleStockvellPack($stockvel_id); // fss = find single stockvell
if (!$fss_result) {
    header("Location: /admin/?error=stockvellnotfound");
    exit();
}
// echo json_encode($fss_result);
// exit();

$convert_period = new PeriodConvert();




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
            <h1 class="h1 text-center"><?= __("Update Pack"); ?>!</h1>
            <p class="text-center"><?= __("You can change any details of the pack and republish the pack to members once again."); ?>!</p>

            <!-- Form start  -->
            <form action="/includes/edit_stockvell.inc.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3 mx-0">
                    <?php // echo inputElement('name', 'Name*', false, 'text');
                    $nm = __("Name") . "*";
                    $pyt = __("Payment") . "*";
                    $ccc = __("Currency") . "*";
                    $pytf = __("Payment Frequency") . "*";
                    $wdf = __("Withdraw Frequency") . "*";
                    $ct = __("Category") . "*";
                    $ap = __("Proof of address (JPG, PNG, PDF)");
                    $dc = __("Description");
                    $sa = __("Start at") . "*";
                    $ea = __("End at") . "*";
                    $tml = __("Total Member Limit");
                    $gid = __("Govt ID Proof (JPG, PNG, PDF)");
                    $agmt = __("You Must Write Agreement About This Stockvel Pack") . "*";
                    echo $input_field->inputText("name", $nm, true, "text", true, $fss_result->name);
                    ?>

                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputText('payment', $pyt, false, 'number', true, $fss_result->payment);
                    echo $input_field->inputSelect('currency', $ccc, false, strtoupper($fss_result->currency), $currency_short);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    $payment_str = $convert_period->convertFromIntToText($fss_result->payment_frequency);
                    $withdraw_str = $convert_period->convertFromIntToText($fss_result->withdraw_frequency);
                    echo $input_field->inputSelect("payment_frequency",  $pytf, false, $payment_str, $with_freq);
                    echo $input_field->inputSelect("withdraw_frequency", $wdf, false, $withdraw_str, $with_freq);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputSelect("category", $ct, false, $fss_result->category, $category_list);
                    echo $input_field->inputText("max_member", $tml, false, 'number', true, $fss_result->max_member);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputDate('start_at', $sa, false, true, $fss_result->start_at);
                    echo $input_field->inputDate('end_at', $ea, false, true, $fss_result->end_at);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputTextarea("description", $dc, true, true, $fss_result->description);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputTextarea("agreement", $agmt, true, true, $fss_result->agreement);
                    ?>
                </div>
                <div class="row mb-3 mx-0">
                    <?php
                    echo $input_field->inputHidden("stockvel_id", $stockvel_id);
                    ?>
                    <div class="col-12">
                        <button type="submit" name="update_stockvell_pack" class="btn btn-primary w-fit"><?= __("Update Stockvel") ?></button>
                    </div>
                </div>
            </form>
            <!-- Form end  -->
            <!-- Update information end -->


        </div>
    </section>
</main>

<?php require_once("./layouts/footer.php"); ?>