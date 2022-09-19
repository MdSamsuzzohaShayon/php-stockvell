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

$rpl_result = []; // rpl = request pack leaders
$is_mos = false; // mos = member of stockvell 
$did_request = false;
if ($is_admin === false) {
    $is_mos = $foundStockvell->isMemberBelongToStockvell($stockvell_id, $member_id); // This return boolean value
    $rtbl_result = $foundStockvell->memberWhoRequestToBeLeader($member_id, $stockvell_id); // rtbl = request to be leader
    if ($rtbl_result->id) {
        $did_request = true;
    }
} else {
    $rpl_result = $foundStockvell->allMembersWhoRequestToBeLeader($stockvell_id);
}
// echo count($rpl_result);


$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}



$input_field = new InputField();

// echo "lid - " . $ssr_result['leader_id'];f
// var_dump($ssr_result['members']);
$leader_index = array_search($ssr_result['leader_id'], array_column($ssr_result['members'], 'id'));
$leader = $ssr_result['members'][$leader_index];
// var_dump($ssr_result['members'][$leader_index]);
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
                    <p> <?php if($leader)echo __("Leader") . " " . $leader['firstname'] . " ". $leader['surname'];  ?> <?php 
                    // if($rpl_key['id'] === )
                    // var_dump($ssr_result);
                    // echo $ssr_result["leader_id"];
                    ?></p>
                    <div class="d-flex">
                        <?php
                        $jp = __("Join Pack");
                        $lp = __("Leave Pack");
                        $lr = __("Leader Request");
                        $st = __("Submit");
                        $cl = __("Cancel");
                        $ap = __("Address Proof");
                        $gid = __("Govt ID Proof");
                        $heading = _("Submit the required document in order to be the leader of the pack!");
                        $para = _("Admin of this site will validate your document and if we think you can be the leader we will appoint you as the leader of the pack");
                        if ($is_admin === false) {
                            $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $ssr_result["id"]);
                            $member_id_hidden_input = $input_field->inputHidden("member_id", $member_id);
                            if ($is_mos) {
                                if ($did_request === false) {
                                    $govt_id_proof = $input_field->inputFile("govt_id_proof", $gid, true, true);
                                    $address_proof = $input_field->inputFile("address_proof", $ap, true, true);
                                    echo "<button id='leader-request-btn' class='btn btn-warning text-capitalize d-block' >$lr</button>";
                                    echo "<form action='/includes/pack_single.inc.php' method='post' id='leader-request-form' enctype='multipart/form-data' class='p-0 m-0 d-none'>
                                            <h2>$heading</h2>
                                            <p>$para</p>
                                            <div class='row my-3'>
                                                $govt_id_proof
                                            </div>
                                            <div class='row mb-3'>
                                                $address_proof
                                            </div>
                                            $stockvell_id_hidden_input
                                            $member_id_hidden_input
                                            <div class='d-flex'>                                       
                                                <button type='submit' class='btn btn-warning text-capitalize' name='member_leader_request_pack'>$st</button>
                                                <button class='btn btn-danger text-capitalize' id='cancel-leader-request'>$cl</button>
                                            </div>
                                        </form>";
                                } else {
                                    $rt = __("The request you have made to become the leader of the pack, we will be reviewed and let you know thank you.");
                                    echo "<p class='text-warning'>$rt</p>";
                                }
                            } else {
                                echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                        $stockvell_id_hidden_input
                                        $member_id_hidden_input
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
                            <p><?= __("Monthly Deposit") ?></p>
                            <h3 class="h3">$<?= $ssr_result['payment']; ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Goal") ?></p>
                            <h3 class="h3"><?= $ssr_result['goal']; ?></h3>
                        </div>
                    </div>
                    <div class="row d-flex highlight-stat justify-content-md-end justify-content-between">
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Total Members") ?></p>
                            <h3 class="h3"><?= $ssr_result['total_members']; ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Pack ID") ?></p>
                            <h3 class="h3"><?= $ssr_result['id']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($is_admin) { ?>
                <div class="row">
                    <p>
                    <h2 class="h2" data-bs-toggle="collapse" href="#leaderCollapse" role="button" aria-expanded="false" aria-controls="leaderCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Members requested to be a leader") ?></h2>
                    </p>
                    <div class="collapse" id="leaderCollapse">
                        <?php
                        // rpl = request pack leaders
                        if (count($rpl_result) <= 0) {
                            $nmrj = __("No one requested to be the leader of the pack");
                            echo "<div class='alert alert-warning'>$nmrj</div>";
                        } else { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered border-warning">
                                    <thead class="bg-warning text-white border-primary">
                                        <tr>
                                            <th scope="col">#<?= __("ID") ?></th>
                                            <th scope="col"><?= __("Name") ?></th>
                                            <th scope="col"><?= __("Email") ?></th>
                                            <th scope="col"><?= __("Government ID Proof") ?></th>
                                            <th scope="col"><?= __("Address Proof") ?></th>
                                            <th scope="col"><?= __("Action") ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                        $ml = __("Appoint leader");
                                        // rpl = requestd pack leader
                                        $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $stockvell_id);
                                        
                                        foreach ($rpl_result as $rpl_key) {
                                            

                                            $member_id_hidden_input = $input_field->inputHidden("member_id", $rpl_key["member_id"]);
                                            $sm_id_hidden_input = $input_field->inputHidden("sm_id", $rpl_key["id"]);
                                            echo "
                                          <tr class='text-capitalize'>
                                             <th>" . $rpl_key["id"] . "</th>
                                             <td>" . $rpl_key["firstname"] . " " . $rpl_key["surname"]  . "</td>
                                             <td>" . $rpl_key["email"] . "</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["govt_id_proof"] . "'>View</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["address_proof"] . "'>View</td>
                                             <td>
                                             <form action='/includes/pack_single.inc.php' method='post'>
                                                $stockvell_id_hidden_input 
                                                $member_id_hidden_input
                                                $sm_id_hidden_input
                                                <button type='submit' name='make_leader_of_pack_submit' class='btn btn-primary'>$ml</button>
                                             </form>
                                             </td>
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
            <?php }            ?>
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Members") ?></h2>
                </p>
                <div class="collapse" id="collapseExample">
                    <?php
                    // psr = pending stockvell result 
                    // $psr_result - getting from dashboard.inc.php
                    if (count($ssr_result['members']) <= 0) {
                        $nmjy = __("No member joined yet");
                        echo "<div class='alert alert-warning'>$nmjy</div>";
                    } else { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered border-warning">
                                <thead class="bg-warning text-white border-primary">
                                    <tr>
                                        <th scope="col">#<?= __("ID") ?></th>
                                        <th scope="col"><?= __("Name") ?></th>
                                        <th scope="col"><?= __("Country") ?></th>
                                        <th scope="col"><?= __("Profession") ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // amr = all member result
                                    // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                                    $leader_user = null;
                                    foreach ($ssr_result['members'] as $amr_key) {
                                        if($ssr_result["leader_id"] === $amr_key["id"]){
                                            $user_and_role = "<td class='text-danger'>" . $amr_key["firstname"] . " " . $amr_key["surname"] . __("(Leader)") . "</td>";
                                            $leader_user = $amr_key;
                                        }else{
                                            $user_and_role = "<td>" . $amr_key["firstname"] . " " . $amr_key["surname"] . "</td>";
                                        }
                                        echo "
                                          <tr class='text-lowercase'>
                                             <th>" . $amr_key["id"] . "</th>
                                             $user_and_role
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
                <h2 class="h2" data-bs-toggle="collapse" href="#agreementCollapse" role="button" aria-expanded="false" aria-controls="agreementCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Agreement") ?></h2>
                </p>
                <div class="collapse" id="agreementCollapse">
                    <?php echo $ssr_result['agreement']; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>