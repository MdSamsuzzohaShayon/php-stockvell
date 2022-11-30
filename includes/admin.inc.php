<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];


// require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/vendor/autoload.php");

use Models\Stockvell\StockvellForms;
use Models\Stockvell\AdminStockvellForms;
use Models\Member\MemberForms;
use Models\Admin\AdminLogin;
use Models\Admin\AdminForms;







// $psr_result = $stockvellPack->getPSC("PENDING"); // psr = pending stockvell result
// $asr_result = $stockvellPack->getPSC("APPROVED"); // apsr = approved stockvell result



if (isset($_POST["login_admin_submit"])) {
    $admin_email = $_POST["email"];
    $admin_password = $_POST["password"];

    // include $ROOT . "/config/Database.php";
    // include $ROOT . "/Models/Login.php";

    $login = new AdminLogin($admin_email, $admin_password);
    $login->loginAdmin();
}


if (isset($_POST["approve_stockvell_pack"])) {
    $stockvell_form = new AdminStockvellForms();
    $stockvel_id = $_GET["stockvel_id"];
    $leader_id = $_GET["leader_id"];
    // update
    $stockvell_form->approveStockvellByAdmin($stockvel_id);
}

// reject_stockvell_pack
if (isset($_POST["reject_stockvell_pack"])) {
    $stockvell_form = new AdminStockvellForms();
    $stockvel_id = $_GET["stockvel_id"];
    // update
    $stockvell_form->rejectStockvellPackByAdmin($stockvel_id, "admin/");
    // echo $stockvell_id;
    // exit();
}



if (isset($_POST["verify_member"])) {
    $member_form = new MemberForms();
    $member_id = $_GET["member_id"];
    // echo ($member_id);
    // exit();
    // // verify
    $member_form->verifyByAdmin($member_id);
}


if (isset($_POST["update_profile_submit"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $admin_id = $_POST['admin_id'];
    $admin_controler = new AdminForms();
    $admin_controler->setAdmin($name, $email, $phone, $password, $password2);
    $admin_controler->updateProfile($admin_id);
}



if (isset($_POST["create_stockvell_pack"])) {
    /**
     * @var getting all inputs
     */
    $name = $_POST["name"];
    $goal = $_POST["goal"];
    $payment = $_POST["payment"];
    $currency = $_POST["currency"];
    $description = $_POST["description"];
    $payment_frequency = $_POST["payment_frequency"];
    $start_at = $_POST["start_at"];
    $end_at = $_POST["end_at"];
    $category = $_POST["category"];
    $max_member = $_POST["max_member"];
    $withdraw_frequency = $_POST["withdraw_frequency"];
    $agreement = $_POST["agreement"];

    // echo json_encode(array(
    //     "name " => $name,
    //     "goal " => $goal,
    //     "payment " => $payment,
    //     "description " => $description,
    //     "payment_frequency " => $payment_frequency,
    //     "category" => $category,
    //     "withdraw_frequency" => $withdraw_frequency,
    // ));
    // exit();

    $stockvell_control = new StockvellForms();
    $stockvell_control->setStockvell($name, $payment, $description, $payment_frequency, $category, $max_member, $withdraw_frequency, $agreement, $currency, $start_at, $end_at);
    $stockvell_control->validateAndCreate();
}


if(isset($_POST["make_leader_of_pack_submit"])){
    $sm_id = $_POST["sm_id"];
    $member_id = $_POST["member_id"];
    $stockvell_id = $_POST["stockvell_id"];
    // echo json_encode(array($sm_id, $member_id, $stockvell_id));
    // exit();
    $member_controler = new StockvellForms();
    $member_controler->makeLeaderOfThePack($sm_id, $member_id, $stockvell_id, 'admin/');
}


if(isset($_POST["suspend_leader_submit"])){
    $stockvell_id = $_POST["stockvell_id"];

    $member_controler = new MemberForms();
    $member_controler->resignLeadership($stockvell_id, "/admin");
}


// close_stockvell_pack
if(isset($_POST["close_stockvell_pack"])){
    $stockvell_form = new AdminStockvellForms();
    $stockvel_id = $_GET["stockvel_id"];
    // update
    $stockvell_form->closeStockvellByAdmin($stockvel_id, "admin/");
}