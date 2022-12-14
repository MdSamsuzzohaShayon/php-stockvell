<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];
// Check for session 
require_once($ROOT . "/vendor/autoload.php");

use Models\Member\MemberForms;




if(isset($_POST["member_update_submit"])){
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    
    $member_id = $_POST['member_id'];
    $firstname = $_POST["firstname"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];
    $city = $_POST["city"];
    $gender = $_POST["gender"];
    $profession = $_POST["profession"];
    $interest = $_POST["interest"];
    $source = $_POST["source"];
    // echo json_encode($govt_id);
    
    $member_forms = new MemberForms();
    $member_forms->setMember($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $source, $city);
    $member_forms->updateDynamicMember($member_id, "edit_member/");
}