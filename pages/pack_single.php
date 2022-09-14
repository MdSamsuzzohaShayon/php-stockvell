<?php
session_start(); // In every single page we should start our session at the top of our code
if (!isset($_SESSION['member_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: /login.php");
    exit();
}

if (!isset($_GET['stockvell_id'])) {
    header("Location: /index.php");
}

$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];
$stockvell_id = $_GET['stockvell_id'];

$is_admin = false;
if (isset($_SESSION['admin_id'])) $is_admin = true;


$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");

use Models\Stockvell\FetchStockvell;
use Utils\InputField;
use Utils\ErrorHandler;


$foundStockvell = new FetchStockvell(); // Variables getting from dashboard.php
// echo $stockvell_id ;
$ssr_result = $foundStockvell->getASingleApprovedStockvell($stockvell_id); // single stockvell result
// echo $ssr_result ["id"];
if (empty($ssr_result['id'])) {
    header("Location: /packs");
    exit();
}

$is_mos = false;
if ($is_admin === false) {
    $is_mos = $foundStockvell->isMemberBelongToStockvell($stockvell_id, $member_id); // mos = member of stockvell
}


$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}



$input_field = new InputField();
?>



<main class="pack_single">
    <section class="section-1">
        <div class="container">
            <?php if ($has_error) echo $err_handler->displayErrors(); ?>
            <div class="row">
                <div class="col-md-6">
                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                    <p><?php echo $ssr_result['description']; ?></p>
                    <p><?= $ssr_result['category']; ?></p>
                    <div class="d-flex">
                    <?php
                    $jp = __("Join Pack");
                    $lp = __("Leave Pack");
                    $lr = __("Leader Request");
                    if ($is_admin === false) {
                        if ($is_mos) {
                            $stockvell_id_input = $input_field->inputHidden("stockvell_id", $ssr_result["id"]);
                            $member_id_input = $input_field->inputHidden("member_id", $member_id);
                            echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                        $stockvell_id_input
                                        $member_id_input
                                        <button type='submit' class='btn btn-warning text-capitalize' name='member_leader_request_pack'>$lr</button>
                                    </form>";
                        } else {
                            $stockvell_id_input = $input_field->inputHidden("stockvell_id", $ssr_result["id"]);
                            $member_id_input = $input_field->inputHidden("member_id", $member_id);
                            echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                        $stockvell_id_input
                                        $member_id_input
                                        <button type='submit' class='btn btn-warning text-capitalize ml-3' name='member_join_pack'>$jp</button>
                                    </form>";
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="row d-flex highlight-stat justify-content-md-end justify-content-between">
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p>Monthly Deposit</p>
                            <h3 class="h3">$<?= $ssr_result['payment']; ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p>Goal</p>
                            <h3 class="h3"><?= $ssr_result['goal']; ?></h3>
                        </div>
                    </div>
                    <div class="row d-flex highlight-stat justify-content-md-end justify-content-between">
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p>Total Members</p>
                            <h3 class="h3"><?= $ssr_result['total_members']; ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p>Pack ID</p>
                            <h3 class="h3"><?= $ssr_result['id']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> Members</h2>
                </p>
                <div class="collapse" id="collapseExample">
                    <?php
                    // psr = pending stockvell result 
                    // $psr_result - getting from dashboard.inc.php
                    if (count($ssr_result['members']) <= 0) {
                        echo "<div class='alert alert-warning'>No member joined yet</div>";
                    } else { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered border-warning">
                                <thead class="bg-warning text-white border-primary">
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Profession</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // amr = all member result
                                    // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                                    foreach ($ssr_result['members'] as $amr_key) {
                                        echo "
                                          <tr class='text-lowercase'>
                                             <th>" . $amr_key["id"] . "</th>
                                             <td>" . $amr_key["firstname"] . " " . $amr_key["surname"]  . "</td>
                                             <td>" . $amr_key["country"] . "</td>
                                             <td>" . $amr_key["profession"] . "</td>
                                          </tr>
                                       ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php }                  ?>
                </div>
            </div>
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#agreementCollapse" role="button" aria-expanded="false" aria-controls="agreementCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> Agreement</h2>
                </p>
                <div class="collapse" id="agreementCollapse">
                    <?php echo $ssr_result['agreement']; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>