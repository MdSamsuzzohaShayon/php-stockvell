<?php
session_start();


$sharelink = null;
if (isset($_GET['sharing'])) {
    // View as sharing
    $sharelink = $_GET['sharing'];
} else {
    /**
     * This page is public now, anyone can access
     */
    // In every single page we should start our session at the top of our code
    // if (!isset($_SESSION['member_id']) && !isset($_SESSION['admin_id'])) {
    //     header("Location: /login.php");
    //     exit();
    // }
}

if (!isset($_GET['stockvel_id'])) {
    header("Location: /packs");
    exit();
}


$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];
$stockvel_id = $_GET['stockvel_id'];
// $SITE_URL = $_ENV["FRONTEND_URL"];
$SITE_URL = "http://stockvell.allinone-office.com";

$is_admin = false;
if (isset($_SESSION['admin_id'])) $is_admin = true;


$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");

use Models\Stockvell\FetchStockvell;
use Models\Stockvell\StockvellForms;
use Models\Member\FetchMember;
use Utils\InputField;
use Utils\ErrorHandler;


$foundStockvell = new FetchStockvell(); // Variables getting from dashboard.php
// echo $stockvel_id ;
$ssr_result = $foundStockvell->getASingleApprovedStockvell($stockvel_id);
if($ssr_result["status"] === "PENDING" && $ssr_result["leader_id"] === null){
    header("Location: /packs");
    exit();
}
// echo json_encode($ssr_result["members"]);
$approved_members = [];
$pending_members = [];
$mi = 0;
while ($mi < count($ssr_result['members'])) {
    // echo json_encode($ssr_result['members'][$mi]);
    // echo $ssr_result['members'][$mi]['m_status'];
    // echo "<br />";
    if ($ssr_result['members'][$mi]['m_status'] === "PENDING") {
        array_push($pending_members, $ssr_result['members'][$mi]);
    }
    if ($ssr_result['members'][$mi]['m_status'] === "APPROVED") {
        array_push($approved_members, $ssr_result['members'][$mi]);
    }
    $mi += 1;
}

// echo $ssr_result ["id"];
if (empty($ssr_result['id'])) {
    header("Location: /packs");
    exit();
}

$rpl_result = []; // rpl = request pack leaders
$is_mos = false; // mos = member of stockvel
$did_request = false;
$cmosd_result = null; // current member of stockvell destail
if ($is_admin === false) {
    $is_mos = $foundStockvell->isMemberBelongToStockvell($stockvel_id, $member_id); // This return boolean value
    $rtbl_result = $foundStockvell->memberWhoRequestToBeLeader($member_id, $stockvel_id); // rtbl = request to be leader
    if ($rtbl_result->id) {
        $did_request = true;
    }
    $cmosd_result = $foundStockvell->getAMemberOfThePack($stockvel_id, $member_id);
} else {
    $rpl_result = $foundStockvell->allMembersWhoRequestToBeLeader($stockvel_id);
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

// echo "lid - " . $ssr_result['leader_id'];
// var_dump($ssr_result);
// $leader_index = array_search($ssr_result['leader_id'], array_column($ssr_result['members'], 'id'));
// echo $leader_index;
$member_fetch = new FetchMember();
$leader = $member_fetch->findMemberByID($ssr_result['leader_id'], '/pack_single');
$withdraw_member = $member_fetch->findMemberByID($ssr_result['withdraw_member_id'], '/pack_single');


// $ssr_result
// Check today's date meed updated date
$today_date = date("Y-m-d");
// var_dump($ssr_result);
// echo "Condition - " . ( $today_date >= $ssr_result['withdraw_at']);
// if ($today_date >= date("Y-m-d")){
if ($today_date >= $ssr_result['withdraw_at']) {
    // if date meet update withdraw member id and date
    $stockvell_control = new StockvellForms();
    $stockvell_control->setMemberToWithdrawFIFO($stockvel_id, $withdraw_member->id);
}

// echo json_encode($ssr_result['members']);

$stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $stockvel_id);
?>



<main class="pack_single">
    <section class="section-1">
        <div class="container">
            <?php if ($has_error) echo $err_handler->displayErrors(); ?>
            <div class="row">
                <div class="col-md-6">
                    <p> <?php if ($leader) echo __("Leader") . " " . $leader->firstname . " " . $leader->surname;  ?> <?php
                                                                                                                        // if($rpl_key['id'] === )
                                                                                                                        // var_dump($ssr_result);
                                                                                                                        // echo $ssr_result["leader_id"];
                                                                                                                        ?></p>
                    <p> <?php if ($withdraw_member) echo __("Withdraw member") . " " . $withdraw_member->firstname . " " . $withdraw_member->surname;  ?> </p>
                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                    <p><?php echo $ssr_result['description']; ?></p>
                    <p><?= $ssr_result['category']; ?></p>
                    <p> <?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                    <div class="d-flex">
                        <?php
                        $jp = __("Join Pack");
                        $rl = __("Resign Leadership");
                        $sl = __("Suspend Leader");
                        $lp = __("Leave Pack");
                        $lr = __("Leader Request");
                        $st = __("Submit");
                        $cl = __("Cancel");
                        $ap = __("Address Proof");
                        $gid = __("Govt ID Proof");
                        $heading = _("Submit the required document in order to be the leader of the pack!");
                        $para = _("Admin of this site will validate your document and if we think you can be the leader we will appoint you as the leader of the pack");
                        $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $ssr_result["id"]);
                        $member_id_hidden_input = $input_field->inputHidden("member_id", $member_id);
                        if ($is_admin === false) {
                            if ($is_mos) {
                                if (!$leader) {
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
                                }
                            } else {
                                if (!$sharelink) {
                                    if ($cmosd_result && $cmosd_result->status === 'PENDING') {
                                        $rta = __("You have requested to join the pack and admin will review your profile and let you in in the pack if he wants to");
                                        echo "<p class='text-warning'>$rta</p>";
                                    } else if (isset($member_id)) {
                                        echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                                $stockvell_id_hidden_input
                                                $member_id_hidden_input
                                                <button type='submit' class='btn btn-warning text-capitalize ml-3' name='member_join_pack'>$jp</button>
                                            </form>";
                                    } else {
                                        echo "<a href='/login' class='btn btn-primary'>Login to join</a>";
                                    }
                                }
                            }
                        }


                        if ($leader) {
                            // var_dump( $leader);
                            // $member_id
                            if ($member_id === $leader->id) {
                                echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                    $stockvell_id_hidden_input
                                    $member_id_hidden_input
                                    <button type='submit' class='btn btn-warning text-capitalize ml-3' name='member_resign_leadership'>$rl</button>
                                </form>";
                            }

                            if ($is_admin === true) {
                                echo "<form action='/includes/pack_single.inc.php' method='post' class='p-0 m-0'>
                                    $stockvell_id_hidden_input
                                    $member_id_hidden_input
                                    <button type='submit' class='btn btn-danger text-capitalize ml-3' name='member_resign_leadership'>$sl</button>
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
                            <?php
                            // $currency_symbol = substr($ssr_result['currency'], strpos($data, "("));
                            $currency_symbol_temp = explode('(', $ssr_result['currency'])[1];
                            $currency_symbol = explode(')', $currency_symbol_temp)[0];
                            ?>
                            <h3 ><?= $currency_symbol . ' ' .  $ssr_result['payment']; ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Category") ?></p>
                            <h3 ><?= $ssr_result['category']; ?></h3>
                        </div>
                    </div>
                    <div class="row d-flex highlight-stat justify-content-md-end justify-content-between">
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Total Members") ?></p>
                            <h3 ><?= count($approved_members) ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Pack ID") ?></p>
                            <h3 ><?= $ssr_result['id']; ?></h3>
                        </div>
                    </div>
                    <div class="row d-flex highlight-stat justify-content-md-end justify-content-between">
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("Start") ?></p>
                            <h3 ><?php  
                            $start_at = date_create($ssr_result['start_at']);
                            echo date_format($start_at,"d/m/Y"); 
                            ?></h3>
                        </div>
                        <div class="highlight-item m-2 p-2 bg-primary text-secondary">
                            <p><?= __("End") ?></p>
                            <h3 ><?php 
                            $start_at = date_create($ssr_result['end_at']);
                            echo date_format($start_at,"d/m/Y"); 
                            ?></h3>
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
                                        // echo json_encode($rpl_result);

                                        foreach ($rpl_result as $rpl_key) {


                                            $member_id_hidden_input = $input_field->inputHidden("member_id", $rpl_key["member_id"]);
                                            $sm_id_hidden_input = $input_field->inputHidden("sm_id", $rpl_key["id"]);
                                            $leader_btn = "";
                                            // echo json_encode(array("rpl_key_id" => $rpl_key["id"], "leader_id" => $leader->id));
                                            if($rpl_key["member_id"] !== $leader->id){
                                                $leader_btn = "<button type='submit' name='make_leader_of_pack_submit' class='btn btn-primary'>$ml</button>";
                                            }
                                            echo "
                                          <tr class='text-capitalize'>
                                             <th>" . $rpl_key["member_id"] . "</th>
                                             <td>" . $rpl_key["firstname"] . " " . $rpl_key["surname"]  . "</td>
                                             <td class='text-lowercase'>" . $rpl_key["email"] . "</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["govt_id_proof"] . "'>View</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["address_proof"] . "'>View</td>
                                             <td>
                                             <form action='/includes/pack_single.inc.php' method='post'>
                                                $stockvell_id_hidden_input 
                                                $member_id_hidden_input
                                                $sm_id_hidden_input
                                                $leader_btn
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


            <!-- List of members start  -->
            <?php
            if (!$sharelink) {
            ?>
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
                                        $withdraw_member = null;
                                        foreach ($approved_members as $amr_key) {
                                            if ($ssr_result["leader_id"] === $amr_key["id"]) {
                                                $user_and_role = "<td class='text-danger'>" . $amr_key["firstname"] . " " . $amr_key["surname"] . __("(L)") . "</td>";
                                                $leader_user = $amr_key;
                                            } else if ($ssr_result["withdraw_member_id"] === $amr_key["id"]) {
                                                $user_and_role = "<td>" . $amr_key["firstname"] . " " . $amr_key["surname"] . __("(W)") . "</td>";
                                                $withdraw_member = $amr_key;
                                            } else {
                                                $user_and_role = "<td>" . $amr_key["firstname"] . " " . $amr_key["surname"] . "</td>";
                                            }
                                            echo "
                                                    <tr class='text-capitalize'>
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
            <?php
            }
            ?>
            <!-- List of members ends  -->

            <!-- List of unapproved members start  -->
            <?php
            if ($leader && $member_id === $leader->id) {
            ?>
                <div class="row">
                    <p>
                    <h2 class="h2" data-bs-toggle="collapse" href="#unapprovedMemberCollapse" role="button" aria-expanded="false" aria-controls="unapprovedMemberCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Member requests") ?></h2>
                    </p>
                    <div class="collapse" id="unapprovedMemberCollapse">
                        <?php
                        // psr = pending stockvell result 
                        // $psr_result - getting from dashboard.inc.php
                        if (count($pending_members) <= 0) {
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
                                            <th scope="col"><?= __("Status") ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // amr = all member result
                                        // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                                        $leader_user = null;
                                        $withdraw_member = null;
                                        foreach ($pending_members as $amr_key) {
                                            if ($ssr_result["leader_id"] === $amr_key["id"]) {
                                                $user_and_role = "<td class='text-danger'>" . $amr_key["firstname"] . " " . $amr_key["surname"] . __("(L)") . "</td>";
                                                $leader_user = $amr_key;
                                            } else if ($ssr_result["withdraw_member_id"] === $amr_key["id"]) {
                                                $user_and_role = "<td>" . $amr_key["firstname"] . " " . $amr_key["surname"] . __("(W)") . "</td>";
                                                $withdraw_member = $amr_key;
                                            } else {
                                                $user_and_role = "<td>" . $amr_key["firstname"] . " " . $amr_key["surname"] . "</td>";
                                            }
                                            $member_approval = null;
                                            if ($amr_key["m_status"] === "PENDING" && $ssr_result["leader_id"] === $member_id) { // Check leader
                                                $member_id_hidden_input = $input_field->inputHidden("member_id", $amr_key["id"]);
                                                $member_approval = "<td>
                                                                        <form method='post' action='/includes/pack_single.inc.php'>
                                                                            $stockvell_id_hidden_input
                                                                            $member_id_hidden_input
                                                                            <button type='submit' name='make_member_of_pack_submit' class='btn btn-primary'>Approve member</button>
                                                                        </form>
                                                                    </td>";
                                            } else {
                                                $member_approval = '<td>' . $amr_key["m_status"] . '</td>';
                                            }
                                            echo "
                                                    <tr class='text-capitalize'>
                                                        <th>" . $amr_key["id"] . "</th>
                                                        $user_and_role
                                                        <td>" . $amr_key["country"] . "</td>
                                                        <td>" . $amr_key["profession"] . "</td>
                                                        $member_approval
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
            <?php
            }
            ?>
            <!-- List of unapproved members ends  -->


            <!-- Agreement start  -->
            <div class="row">
                <p>
                <h2 class="h2" data-bs-toggle="collapse" href="#agreementCollapse" role="button" aria-expanded="false" aria-controls="agreementCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Agreement") ?></h2>
                </p>
                <div class="collapse" id="agreementCollapse">
                    <?php echo $ssr_result['agreement']; ?>
                </div>
            </div>
            <!-- Agreement end  -->

            <!-- Share link to facebook start  -->
            <?php
            $backend_url = $_ENV['BACKEND_URL'];
            $shareable_link = "$backend_url/pack_single/?stockvel_id=$stockvel_id";
            ?>
            <div class="social-share d-flex justify-content-start align-items center">
                <div class="fb-share-button p-0 me-2 shadow bg-body rounded" data-href="<?= $SITE_URL . $shareable_link ?>" data-layout="button" data-size="large">
                    <a target="_blank" class="btn btn-white p-3" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">
                        <img src="/public/icons/fb.svg" alt="">
                    </a>
                </div>
                <!-- Share link to facebook end  -->
                <a target="_blink" href="https://twitter.com/intent/tweet?text=<?= $SITE_URL . $shareable_link ?>" class="btn btn-white p-3 me-2 shadow bg-body rounded">
                    <img src="/public/icons/twitter.svg" alt="">
                </a>
                <a target="_blink" href="https://www.linkedin.com/shareArticle?mini=true&url=<?=  $SITE_URL . $shareable_link ?> ?>&title=LinkedIn%20Developer%20Network&summary=<?= $SITE_URL . $shareable_link  ?>&source=Stockvel" class="btn btn-white p-3 me-2 shadow bg-body rounded">
                    <img src="/public/icons/linkedin.svg" alt="">
                </a>
            </div>
    </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>