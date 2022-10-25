<?php 
session_start(); 

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once("./layouts/header.php"); 

?>


<main>
    <section class="section-1">
        <div class="container">
            Privacy Policy
        </div>
    </section>
</main>

<?php require_once("./layouts/footer.php"); ?>