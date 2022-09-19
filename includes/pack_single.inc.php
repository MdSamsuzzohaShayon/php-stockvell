<?php 

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");

use Models\Member\MemberForms;
use Models\Stockvell\StockvellForms;

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


if(isset($_POST["member_leader_request_pack"])){
    // Hidden inputs
    $member_id = $_POST["member_id"];
    $stockvell_id = $_POST["stockvell_id"];

    // $govt_id = $_FILES["govt_id"];
    $govt_id_proof = $_FILES["govt_id_proof"];
    $address_proof = $_FILES["address_proof"];

    $member_controler = new MemberForms();
    $member_controler->submitLeaderRequest($member_id,  $stockvell_id, $govt_id_proof, $address_proof);
}



// Test this function 
if(isset($_POST["make_leader_of_pack_submit"])){
    $sm_id = $_POST["sm_id"];
    $member_id = $_POST["member_id"];
    $stockvell_id = $_POST["stockvell_id"];
    $member_controler = new StockvellForms();
    $member_controler->makeLeaderOfThePack($sm_id, $member_id, $stockvell_id);
}