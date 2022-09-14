<?php 

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

use Models\Member\MemberForms;

if(isset($_POST["member_join_pack"])){
    $member_id = $_POST["member_id"];
    $stockvell_id = $_POST["stockvell_id"];

    $member_controler = new MemberForms();
    $member_controler->memberJoinPack($member_id, $stockvell_id);
}


if(isset($_POST["member_leave_pack"])){
    $member_id = $_POST["member_id"];
    $stockvell_id = $_POST["stockvell_id"];

    $member_controler = new MemberForms();
    $member_controler->memberLeavePack($member_id, $stockvell_id);
}