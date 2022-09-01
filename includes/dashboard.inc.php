<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/stockvell.classes.php";


$foundMember = new Stockvell(); // Variables getting from dashboard.php
$foundMember->setMember($member_email, $member_id );
$result = $foundMember->getCurrentMember();
$psr_result = $foundMember->getAllPendingStockvell("PENDING", false); // psr = pending search result
$asr_result = $foundMember->getAllApprovedStockvellOfAMember("APPROVED", $member_id); // asr = approved search result



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
  
  $stockvell_control = new StockvellForms($name, $goal, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement);
  $stockvell_control->validateAndCreate();

}
?>