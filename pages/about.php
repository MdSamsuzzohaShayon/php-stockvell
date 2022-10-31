<?php
session_start(); // In every single page we should start our session at the top of our code

$ROOT = $_SERVER['DOCUMENT_ROOT'];

// Check for session 
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");


?>



<main class="about">
    <section class="section section-1">
        <div class="container">
            <div class="alert alert-primary">About section</div>
        </div>
    </section>
</main>

<?php require_once("./layouts/footer.php"); ?>