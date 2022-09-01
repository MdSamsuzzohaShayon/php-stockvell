<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/stockvell.classes.php";
include $ROOT . "/classes/member.classes.php";


$stockvellPack = new Stockvell(null, null);
$admin_id = $_SESSION['admin_id'];
$is_admin = null;
$admin_id ? $is_admin = true : $is_admin = false;
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
    $stockvell_form = new MemberForms();
    $member_id= $_GET["member_id"];
    // verify
    $stockvell_form->verifyByAdmin($member_id);
}
