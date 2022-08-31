<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/stockvell.classes.php";


$stockvellPack = new Stockvell();


$psr_result = $stockvellPack->getPSC("PENDING"); // psr = pending stockvell result
$asr_result = $stockvellPack->getPSC("APPROVED"); // apsr = approved stockvell result

if(isset($_POST["approve_stockvell"])){
    $stockvell_id = $_GET["stockvell_id"];
    // update
    // $stockvellPack->updateStockvell($stockvell_id, array("status" => "APPROVED"));
}


