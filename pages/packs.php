<?php
session_start(); // In every single page we should start our session at the top of our code
// $member_email = $_SESSION['member_email'];
// $member_id = $_SESSION['member_id'];
if (!isset($_SESSION['member_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: /login.php");
    exit();
}

// !isset($_SESSION['admin_id'])
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/includes/packs.inc.php");
?>



<main class="packs">
    <section class="section-1">
        <div class="container">
            <div class="pack-caption text-center">
                <h1 class="h1"><?= __("All Stockvell Pack"); ?></h1>
                <p><?= __("All the packs created by any members and approved by the admin will show here"); ?> </p>
            </div>

            <div class="pack-list">
                <div class="d-flex flex-wrap justify-content-between">
                    <?php
                    if (count($asr_result) <= 0) {
                        echo "<div class='alert alert-warning'>No pack found</div>";
                    } else {
                        // psr = pending stockvell result 
                        foreach ($asr_result as $asr_key) { ?>
                            <div class="card pack-item mb-5">
                                <div class="card-body text-bg-secondary text-primary">
                                    <h4 class="h4">ID#<?= $asr_key['id'] ?></h4>
                                    <div class="d-flex justify-content-between w-full">
                                        <p><?= __("Monthly deposit"); ?></p>
                                        <?php
                                        $currency_symbol_temp = explode('(', $asr_key['currency'])[1];
                                        $currency_symbol = explode(')', $currency_symbol_temp)[0];
                                        ?>
                                        <p><?= $currency_symbol . ' ' .  $asr_key['payment']; ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between w-full">
                                        <p><?= __("Category"); ?></p>
                                        <p><?= $asr_key['category'] ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between w-full">
                                        <p><?= __("Goal"); ?></p>
                                        <p><?= $asr_key['goal'] ?></p>
                                    </div>
                                    <a href="/pack_single.php?stockvell_id=<?= $asr_key['id'] ?>" class="btn btn-warning text-decoration-none text-white"><?= __("Details"); ?></a>
                                </div>
                            </div>
                    <?php }
                    } ?>

                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>