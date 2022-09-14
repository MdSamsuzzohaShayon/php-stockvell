<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];
include $ROOT . "/vendor/autoload.php";



// include $ROOT . "/config/database.php";
// include $ROOT . "/classes/stockvell.classes.php";

use Models\Stockvell\FetchStockvell;


$foundMember = new FetchStockvell(); // Variables getting from dashboard.php
$asr_result = $foundMember->getStockvellByStatus("APPROVED"); // asr = approved search result




?>