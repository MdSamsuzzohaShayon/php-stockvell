<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];
include $ROOT . "/vendor/autoload.php";



// include $ROOT . "/config/database.php";
// include $ROOT . "/classes/stockvell.classes.php";

use Models\Stockvell\Stockvell;


$foundMember = new Stockvell(); // Variables getting from dashboard.php
$asr_result = $foundMember->getAllApprovedStockvell("APPROVED", false); // asr = approved search result




?>