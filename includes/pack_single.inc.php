<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/vendor/autoload.php";


use Models\Stockvell\Stockvell;


$foundStockvell = new Stockvell(); // Variables getting from dashboard.php
// echo $stockvell_id ;
$ssr_result = $foundStockvell->getASingleStockvell($stockvell_id); // single stockvell result
if(empty($ssr_result['id'])){
    header("Location: /packs");
    exit();
}

$is_mos = false; 
if($is_admin === false){
    $is_mos = $foundStockvell->isMemberBelongToStockvell($stockvell_id, $member_id); // mos = member of stockvell
}




?>