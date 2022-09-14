<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];
// Check for session 
require_once($ROOT . "/vendor/autoload.php");

use Models\Stockvell\StockvellForms;



if(isset($_POST["update_stockvell_pack"])){
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    
    $stockvell_id = $_POST['stockvell_id'];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $payment = $_POST["payment"];
    $payment_frequency = $_POST["payment_frequency"];
    $category = $_POST["category"];
    $withdraw_frequency = $_POST["withdraw_frequency"];
    $agreement = $_POST["agreement"];

    
    $member_forms = new StockvellForms();
    $input_list = $member_forms->setStockvellPropertyArray($stockvell_id, $name, $description, $payment, $payment_frequency, $category, $withdraw_frequency, $agreement);
    // echo json_encode($input_list);
    // exit();
    if($member_forms->updateStockvell($stockvell_id, $input_list)){
        header("Location: /edit_stockvell/?stockvell_id=$stockvell_id&error=none");
        exit();
    }else{
        header("Location: /edit_stockvell/?stockvell_id=$stockvell_id&error=stmtfailed");
        exit();
    }
}