<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/vendor/autoload.php";


use Models\Stockvell\Stockvell;


$foundStockvell = new Stockvell(); // Variables getting from dashboard.php
// echo $stockvell_id ;
$ssr_result = $foundStockvell->getASingleStockvell($stockvell_id); // single stockvell result




?>