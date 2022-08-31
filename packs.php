<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];
if (!isset($member_email)) {
    header("Location: /login.php");
    exit();
}
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");
?>



<main class="packs">
    <section class="section-1">
        <div class="container">
            <div class="pack-caption text-center">
                <h1 class="h1"><?= __("All Stockvell Pack"); ?></h1>
                <p><?= __("All the pack created by any members and accproved by admin will show here"); ?> </p>
            </div>

            <div class="pack-list">
                <div class="row">
                    <div class="col-12 col-md-4 bg-secondary">
                        <div class="d-flex flex-column p-4">
                            <h4 class="h4">SL#4</h4>
                            <div class="d-flex justify-content-between w-full">
                                <p>Monthly deposit</p>
                                <p>$120</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Total members</p>
                                <p>20</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Goal</p>
                                <p>House</p>
                            </div>
                            <a href="/packs.php?single_pack_id=3" class="btn btn-warning text-decoration-none text-white">Details</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 bg-secondary">
                        <div class="d-flex flex-column p-4">
                            <h4 class="h4">SL#4</h4>
                            <div class="d-flex justify-content-between w-full">
                                <p>Monthly deposit</p>
                                <p>$120</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Total members</p>
                                <p>20</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Goal</p>
                                <p>House</p>
                            </div>
                            <a href="/packs.php?single_pack_id=3" class="btn btn-warning text-decoration-none text-white">Details</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 bg-secondary">
                        <div class="d-flex flex-column p-4">
                            <h4 class="h4">SL#4</h4>
                            <div class="d-flex justify-content-between w-full">
                                <p>Monthly deposit</p>
                                <p>$120</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Total members</p>
                                <p>20</p>
                            </div>
                            <div class="d-flex justify-content-between w-full">
                                <p>Goal</p>
                                <p>House</p>
                            </div>
                            <a href="/packs.php?single_pack_id=3" class="btn btn-warning text-decoration-none text-white">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>