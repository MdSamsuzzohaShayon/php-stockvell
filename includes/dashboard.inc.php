<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/dashboard.classes.php";


$foundMember = new DashboardController($member_email);
$result = $foundMember->getCurrentMember();
// $result = $resultArr[0];
// var_dump($result);
// echo $result->id;
// echo $result->;
?>