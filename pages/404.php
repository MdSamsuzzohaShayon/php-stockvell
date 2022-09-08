<?php
session_start(); // In every single page we should start our session at the top of our code
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT."/layouts/header.php");
?>



<main class="page404">
    <div class="container my-5">
        <div class="alert alert-danger">404 page not found</div>
        <a href="/index.php" class="text-danger">Back to home page</a>
    </div>
</main>

<?php require_once($ROOT."/layouts/footer.php"); ?>