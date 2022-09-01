<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/stockvell.classes.php";


$foundStockvell = new Stockvell(); // Variables getting from dashboard.php
// echo $stockvell_id ;
$ssr_result = $foundStockvell->getASingleStockvell($stockvell_id); // single stockvell result




?>