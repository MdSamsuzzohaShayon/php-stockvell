<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];


// require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/vendor/autoload.php");

use Models\Stockvell\StockvellForms;
use Models\Stockvell\AdminStockvellForms;
use Models\Member\MemberForms;
use Models\Admin\AdminLogin;







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


if (isset($_POST["approve_stockvell"])) {
    $stockvell_form = new AdminStockvellForms();
    $stockvell_id = $_GET["stockvell_id"];
    $leader_id = $_GET["leader_id"];
    // update
    $stockvell_form->approveStockvellByAdmin($stockvell_id, array("status" => "APPROVED"), $leader_id);
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
    $admin_id = $_SESSION['admin_id'];

    $admin_def->setAdmin($admin_id, $name, $email, $phone, $password, $password2);
    $admin_def->updateAdminProfile();
}



if (isset($_POST["create_stockvell_pack"])) {
    /**
     * @var getting all inputs
     */
    $name = $_POST["name"];
    $goal = $_POST["goal"];
    $payment = $_POST["payment"];
    $description = $_POST["description"];
    $payment_frequency = $_POST["payment_frequency"];
    $category = $_POST["category"];
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
    $stockvell_control->setStockvell($name, $goal, $payment, $description, $payment_frequency, $category, $withdraw_frequency, $agreement);
    $stockvell_control->validateAndCreate();
}
