<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/dashboard.classes.php";


$foundMember = new DashboardController();
$foundMember->setCurrentMember($member_email, $member_id ); // Variables getting from dashboard.php
$result = $foundMember->getCurrentMember();
$psr_result = $foundMember->getAllPendingStockvell("PENDING");


//  Create stockvell pack
if(isset($_POST['create-stockvell'])){
    /**
   * @var getting all inputs
   */
  $name = $_POST["name"];
  $goal = $_POST["goal"];
  $payment = $_POST["payment"];
  $payment_frequency = $_POST["payment_frequency"];
  $category = $_POST["category"];
  $withdraw_frequency = $_POST["withdraw_frequency"];
  $agreement = $_POST["agreement"];

  
  $stockvell_control = new StockvellController($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement);
  $stockvell_control->validateAndCreate();

}
?>