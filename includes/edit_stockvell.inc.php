<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];
// Check for session 
require_once($ROOT . "/vendor/autoload.php");

use Models\Stockvell\StockvellForms;



if(isset($_POST["update_stockvell_pack"])){
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    
    $stockvel_id = $_POST['stockvel_id'];
    $name = $_POST["name"];
    $payment = $_POST["payment"];
    $currency = $_POST["currency"];
    $payment_frequency = $_POST["payment_frequency"];
    $withdraw_frequency = $_POST["withdraw_frequency"];
    $category = $_POST["category"];
    $max_member = $_POST["max_member"];
    $start_at = $_POST["start_at"];
    $end_at = $_POST["end_at"];
    $description = $_POST["description"];
    $agreement = $_POST["agreement"];

    
    $member_forms = new StockvellForms();
    $input_list = $member_forms->setStockvellPropertyArray($stockvel_id, $name, $payment, $currency,  $payment_frequency, $withdraw_frequency, $category, $max_member, $start_at, $end_at, $description, $agreement);
    if($member_forms->updateStockvell($stockvel_id, $input_list)){
        header("Location: /edit_stockvell/?stockvel_id=$stockvel_id&error=none");
        exit();
    }else{
        header("Location: /edit_stockvell/?stockvel_id=$stockvel_id&error=stmtfailed");
        exit();
    }
}