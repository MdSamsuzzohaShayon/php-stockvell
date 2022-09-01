<?php 
$ROOT = $_SERVER['DOCUMENT_ROOT'];



include $ROOT . "/config/database.php";
include $ROOT . "/classes/stockvell.classes.php";


$foundMember = new Stockvell(); // Variables getting from dashboard.php
$asr_result = $foundMember->getAllApprovedStockvell("APPROVED", false); // asr = approved search result




?>