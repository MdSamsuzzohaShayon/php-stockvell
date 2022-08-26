<?php
session_start();
if (isset($_POST["submit"])) {
    /**
     * @var getting all inputs
     */
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    $member_id = $_SESSION['member_id'];

    $firstname = $_POST["firstname"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $profession = $_POST["profession"];
    $interest = $_POST["interest"];
    $govt_id = $_POST["govt_id"];
    $source = $_POST["source"];




    include $ROOT . "/config/database.php";
    include $ROOT . "/classes/member.classes.php";

    $member = new MemberController($firstname, $surname, $email, $password, $password2, $country, $phone, $gender, $profession, $interest, $govt_id, $source);
    $member->updateDynamicMember($member_id);

    header("location: /login.php");
}else{
    header("Location: /dashboard.php");
}
