<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];


require_once($ROOT . "/vendor/autoload.php");
// include $ROOT . "/config/database.php";
// include $ROOT . "/classes/stockvell.classes.php";
// include $ROOT . "/classes/member.classes.php";
// include $ROOT . "/classes/admin.classes.php";

use Models\Stockvell\Stockvell;
use Models\Stockvell\AdminStockvellForms;
use Models\Member\MemberForms;
use Models\Admin\Admin;



$admin_id = $_SESSION['admin_id'];
$is_admin = null;
$admin_id ? $is_admin = true : $is_admin = false;

$admin_def = new Admin();
// echo $_SESSION["admin_id"];
// exit();
$fabi_result = $admin_def->findAdminById($admin_id);
// echo json_encode($fabi_result);


$stockvellPack = new Stockvell(null, null);
$apsr_result = $stockvellPack->getAllPendingStockvell('PENDING', $is_admin); // apsr = all pending stockvell result
$aasr_result = $stockvellPack->getAllApprovedStockvell('APPROVED'); // aasr = all approved stockvell result
$amr_result = $stockvellPack->getAllMembers($is_admin);



// $psr_result = $stockvellPack->getPSC("PENDING"); // psr = pending stockvell result
// $asr_result = $stockvellPack->getPSC("APPROVED"); // apsr = approved stockvell result

if(isset($_POST["approve_stockvell"])){
    $stockvell_form = new AdminStockvellForms();
    $stockvell_id = $_GET["stockvell_id"];
    $leader_id = $_GET["leader_id"];
    // update
    $stockvell_form->approveStockvellByAdmin($stockvell_id, array("status" => "APPROVED"), $leader_id);
}



if(isset($_POST["verify_member"])){
    $member_form = new MemberForms();
    $member_id= $_GET["member_id"];
    // echo ($member_id);
    // exit();
    // // verify
    $member_form->verifyByAdmin($member_id);
}


if(isset($_POST["update_profile_submit"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $admin_id = $_SESSION['admin_id'];

    $admin_def->setAdmin($admin_id, $name, $email, $phone, $password, $password2);
    $admin_def->updateAdminProfile();
}
