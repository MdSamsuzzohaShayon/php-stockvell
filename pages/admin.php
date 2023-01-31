<?php
session_start(); // In every single page we should start our session at the top of our code
if (isset($_SESSION['member_id'])) {
    header("Location: /dashboard.php");
    exit();
}

$admin_id = null;

if (!isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];

// Check for session 
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");


use Utils\ErrorHandler;
use Utils\InputField;
use Models\Stockvell\FetchStockvell;
use Models\Admin\FetchAdmin;
use Models\Member\FetchMember;
use Utils\PeriodConvert;
use Utils\HTMLMessage;


/**
 * @ fetch essential data
 */
$admin_id = $_SESSION['admin_id'];
$is_admin = null;
$admin_id ? $is_admin = true : $is_admin = false;

$admin_def = new FetchAdmin();
$fabi_result = $admin_def->findAdminById($admin_id);
// echo json_encode($fabi_result);


$stockvell_pack = new FetchStockvell();
$member_controler = new FetchMember();

$apsr_result = $stockvell_pack->getStockvellByStatus("PENDING"); // apsr = all pending stockvell result
// var_dump($apsr_result);
// exit();
$aasr_result = $stockvell_pack->getStockvellByStatus('APPROVED'); // aasr = all approved stockvell result
$acrsr_result = $stockvell_pack->getStockvellByStatus('CLOSE_REQUESTED'); // aasr = all closed requested stockvell result
$amr_result = $stockvell_pack->getAllMembers($is_admin);

/**
 * @detail of stockvell pack
 */


$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}

$single_stockvel_id = $_GET["stockvel_id"];


$input_field = new InputField();
$with_per = new PeriodConvert();


$ml = __("Appoint leader");
$av = __("Approve");
$cl = __("Close");
$rj = __("Reject");
$vw = __("View");
$vd = __("Verified");
$et = __("Edit");
$lr = __("Leader Requests");
$dl = __("Detail");
$am = __("Approve member");

?>


    <main class="admin">
        <!-- This section is for authenticated admin  -->
        <?php
        if ($admin_id) {
            require_once($ROOT . "/includes/admin.inc.php");

            $npf = __("No pack found");
            ?>
            <!-- Authenticated content start  -->
            <section class="section-2">
                <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
                    <!--
                    SIDEBAR MENU START
                    ========================================================================================
                    -->
                    <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
                        <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
                            <li role="button" data-item="admin-profile"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                                <img class="mx-md-4 mx-0" height="33" src="/public/icons/profile-icon.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("Profile"); ?></p>
                            </li>
                            <li role="button" data-item="all-members"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                                <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("All members"); ?></p>
                            </li>
                            <li role="button" data-item="approved-pack"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row"
                                aria-current="true">
                                <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("Approved Pack"); ?></p>
                            </li>
                            <li role="button" data-item="pending-pack"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                                <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("Pending Pack"); ?></p>
                            </li>
                            <li role="button" data-item="close-pack"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                                <img class="mx-md-4 mx-0" src="/public/icons/close-pack.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("Closed Packs"); ?></p>
                            </li>
                            <li role="button" data-item="add-pack"
                                class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                                <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                                <p class="m-0 px-3 d-md-block d-none"><?= __("Add pack!"); ?></p>
                            </li>
                        </ul>
                    </div>
                    <!--
                    SIDEBAR MENU END
                    ========================================================================================
                    -->
                    <div class="col-md-9 sidebar-content">
                        <?php if ($has_error) echo $err_handler->displayErrors(); ?>
                        <!--
                           PROFILE CONTENT START
                           =================================================================================================
                        -->
                        <div class="content my-4 admin-profile-content d-block">
                            <h1 class="h1 text-center"><?= __("Admin Profile!"); ?></h1>
                            <p class="text-center"><?= __("Edit admins information"); ?></p>
                            <form action="/includes/admin.inc.php" method="post">
                                <div class="row mb-3">
                                    <?php
                                    $nm = __("Name");
                                    $eml = __("Email");
                                    echo $input_field->inputText("name", $nm, false, "text", false, $fabi_result->name);
                                    echo $input_field->inputText("email", $eml, false, "email", false, $fabi_result->email);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    $pn = __("Phone");
                                    echo $input_field->inputPhone("phone", $pn, true, false, $country_code_list, $fabi_result->phone);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    $pw = __("Password");
                                    $pw2 = __("Confirm Password");
                                    echo $input_field->inputText("password", $pw, false, "password", false);
                                    echo $input_field->inputText("password2", $pw2, false, "password", false);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    echo $input_field->inputHidden('admin_id', $admin_id);
                                    ?>
                                </div>
                                <button type="submit" name="update_profile_submit"
                                        class="btn btn-primary"><?= __("Update Pack"); ?></button>
                            </form>
                        </div>
                        <!--
                           PROFILE CONTENT END
                           =================================================================================================
                        -->

                        <!--
                           ALL MEMBERS CONTENT START
                           =================================================================================================
                        -->
                        <div class="content my-4 all-members-content d-none">
                            <h1 class="h1 text-center"><?= __("All members"); ?>!</h1>
                            <p class="text-center"><?= __("All members pieces of information which are verified or not"); ?></p>
                            <?php
                            // psr = pending stockvell result
                            // $psr_result - getting from dashboard.inc.php
                            if (count($amr_result) <= 0) {
                                echo "<div class='alert alert-warning'>$npf</div>";
                            } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered border-warning">
                                        <thead class="bg-warning text-capitalize">
                                        <colgroup span="10"></colgroup>
                                        <colgroup span="2"></colgroup>
                                        <tr class="bg-warning text-white border-primary" scope="colgroup">
                                            <th colspan="10"><?= __("User Details") ?></th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                        <tr class="bg-warning text-white border-primary">
                                            <th>#<?= __("ID"); ?></th>
                                            <th scope="col"><?= __("Firstname"); ?></th>
                                            <th scope="col"><?= __("Surname"); ?></th>
                                            <th scope="col"><?= __("Email"); ?></th>
                                            <th scope="col"><?= __("Country"); ?></th>
                                            <th scope="col"><?= __("Phone"); ?></th>

                                            <th scope="col"><?= __("Gender"); ?></th>
                                            <th scope="col"><?= __("Profession"); ?></th>
                                            <th scope="col"><?= __("Interest"); ?></th>
                                            <th scope="col"><?= __("Source"); ?></th>

                                            <th scope="col"><?= $et ?></th>
                                            <th scope="col"><?= __("Verification"); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // amr = all member result
                                        // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                                        foreach ($amr_result as $amr_key) {
                                            # If not verified add a form to verify
                                            $verified_content = "<form class='p-0 m-0' action='/includes/admin.inc.php?member_id=" . $amr_key["id"] . "' method='post'>
                                                         <button type='submit' name='verify_member' class='btn btn-warning'>$av</button>  
                                                      </form>";
                                            // If already verified show the text verified
                                            if (intval($amr_key['is_verified']) === 1) {
                                                $verified_content = $vd;
                                            }
                                            // echo $amr_key["id"];
                                            echo "
                                          <tr class='text-lowercase'>
                                             <th>" . $amr_key["id"] . "</th>
                                             <td>" . $amr_key["firstname"] . "</td>
                                             <td>" . $amr_key["surname"] . "</td>
                                             <td>" . $amr_key["email"] . "</td>
                                             <td>" . $amr_key["country"] . "</td>
                                             <td>" . $amr_key["phone"] . "</td>

                                             <td>" . $amr_key["gender"] . "</td>
                                             <td>" . $amr_key["profession"] . "</td>
                                             <td>" . $amr_key["interest"] . " </td>
                                             <td>" . $amr_key["source"] . "</td>

                                             <td><a href='/edit_member/?member_id=" . $amr_key['id'] . "' class='btn btn-primary'>$et</a></td>
                                             <td>$verified_content</td>
                                          </tr>
                                          ";
                                            // <td><a href='/edit_member/?member_id=" . $amr_key["id"] . ">Edit</a></td>
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                        <!--
                           ALL MEMBERS CONTENT END
                           =================================================================================================
                        -->

                        <!--
                           AAPPROVED PACK CONTENT START
                           =================================================================================================
                        -->
                        <div class="content approved-pack-content d-none">
                            <?php
                            // psr = pending stockvell result
                            // $psr_result - getting from dashboard.inc.php
                            if (isset($single_stockvel_id)) {
                                $rpl_result = $stockvell_pack->allMembersWhoRequestToBeLeader($single_stockvel_id); // rpl = request pack leaders
                                $ssr_result = $stockvell_pack->getASingleApprovedStockvell($single_stockvel_id); // ssr = single stockvell result
                                $leader = $member_controler->findMemberByID($ssr_result['leader_id'], '/admin');

                                $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $single_stockvel_id);

                                $approved_members = [];
                                $pending_members = [];
                                $mi = 0;
                                while ($mi < count($ssr_result['members'])) {
                                    if ($ssr_result['members'][$mi]['m_status'] === "PENDING") {
                                        array_push($pending_members, $ssr_result['members'][$mi]);
                                    }
                                    if ($ssr_result['members'][$mi]['m_status'] === "APPROVED") {
                                        array_push($approved_members, $ssr_result['members'][$mi]);
                                    }
                                    $mi += 1;
                                }

                                ?>
                                <!-- stockvell detail start  -->
                                <div class="row mb-3">
                                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                                    <p><?php echo $ssr_result['description']; ?></p>
                                    <p><?= $ssr_result['category']; ?></p>
                                    <p> <?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                                    <?php if ($leader) {
                                        $lr = __('Leader');
                                        $sl = __('Suspend Leader');
                                        echo "<div class='d-flex'> 
                                 <p>
                                    $lr $leader->firstname  $leader->surname
                                 </p>
                                 <form action='/includes/admin.inc.php' method='post'>
                                    $stockvell_id_hidden_input
                                    <button type='submit' name='suspend_leader_submit' class='btn btn-danger mx-3'>$sl</button>
                                 </form>
                              </div>";
                                    } ?>
                                </div>
                                <!-- stockvell detail end  -->
                                <!-- Leader request start  -->
                                <div class="row mb-3">
                                    <p>
                                    <h2 class="h2" data-bs-toggle="collapse" href="#leaderCollapse" role="button"
                                        aria-expanded="false" aria-controls="leaderCollapse"><span><img
                                                    src="/public/icons/down-arrow.svg" height="30"
                                                    alt=""></span> <?= __("Members requested to be a leader") ?></h2>
                                    </p>
                                    <div class="collapse" id="leaderCollapse">
                                        <?php
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
                                                    // rpl = requestd pack leader

                                                    foreach ($rpl_result as $rpl_key) {
                                                        $member_id_hidden_input = $input_field->inputHidden("member_id", $rpl_key["member_id"]);
                                                        $sm_id_hidden_input = $input_field->inputHidden("sm_id", $rpl_key["id"]);
                                                        echo "
                                                              <tr class='text-capitalize'>
                                                                 <th>" . $rpl_key["id"] . "</th>
                                                                 <td>" . $rpl_key["firstname"] . " " . $rpl_key["surname"] . "</td>
                                                                 <td class='text-lowercase'>" . $rpl_key["email"] . "</td>
                                                                 <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["govt_id_proof"] . "'>View</td>
                                                                 <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["address_proof"] . "'>View</td>
                                                                 <td>
                                                                 <form action='/includes/admin.inc.php' method='post'>
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
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Leader request ends  -->

                                <!-- approved members start  -->
                                <div class="row mb-3">
                                    <p>
                                    <h2 class="h2" data-bs-toggle="collapse" href="#approvedMemberCollapse"
                                        role="button" aria-expanded="false" aria-controls="approvedMemberCollapse">
                                        <span><img src="/public/icons/down-arrow.svg" height="30"
                                                   alt=""></span> <?= __("Members") ?></h2>
                                    </p>
                                    <div class="collapse" id="approvedMemberCollapse">
                                        <?php
                                        if (count($approved_members) <= 0) {
                                            $nmjy = __("No member found");
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
                                                        $member_approval
                                                        <td>" . $amr_key["m_status"] . "</td>
                                                    </tr>
                                                ";
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- approved members end  -->

                                <!-- Pending members start  -->
                                <div class="row mb-3">
                                    <p>
                                    <h2 class="h2" data-bs-toggle="collapse" href="#unapprovedMemberCollapse"
                                        role="button" aria-expanded="false" aria-controls="unapprovedMemberCollapse">
                                        <span><img src="/public/icons/down-arrow.svg" height="30"
                                                   alt=""></span> <?= __("Member requests") ?></h2>
                                    </p>
                                    <div class="collapse" id="unapprovedMemberCollapse">
                                        <?php
                                        if (count($pending_members) <= 0) {
                                            $nmjy = __("No member found");
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
                                                        $member_id_hidden_input = $input_field->inputHidden("member_id", $amr_key["id"]);
                                                        $member_approval = "<td>
                                                                  <form method='post' action='/includes/pack_single.inc.php'>
                                                                        $stockvell_id_hidden_input
                                                                        $member_id_hidden_input
                                                                        <button type='submit' name='make_member_of_pack_submit' class='btn btn-primary'>$am</button>
                                                                  </form>
                                                               </td>";
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
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Pending members end  -->
                                <?php
                            } else {
                                ?>
                                <!-- All stockvells (approved, pending, rejected)
                             See whoever requested to become leader -->
                                <h1 class="h1 text-center"><?= __("All approved packs"); ?>!</h1>
                                <p class="text-center"><?= __("You can find all the stockvel packs that are made or requested for approval. If you cannot find a pack that you have created, the pack has been rejected. Try creating another one with proper information!"); ?>
                                    !</p>
                                <?php
                                $nf = __("No pack found");
                                if (count($aasr_result) <= 0) {
                                    echo "<div class='alert alert-warning'>$nf</div>";
                                } else { ?>
                                    <div class="table-responsive d-block" id="approved-stockvell-list">
                                        <table class="table table-bordered border-warning">
                                            <thead class="bg-warning text-white border-primary">
                                            <tr class="bg-warning text-white border-primary">
                                                <th scope="col"><?= __("ID"); ?></th>
                                                <th scope="col"><?= __("Name"); ?></th>
                                                <th scope="col"><?= __("Category"); ?></th>
                                                <th scope="col"><?= __("Payment"); ?></th>
                                                <th scope="col"><?= __("Currency"); ?></th>
                                                <th scope="col"><?= __("Payment Period"); ?></th>
                                                <th scope="col"><?= __("Leader"); ?></th>
                                                <th scope="col"><?= __("Withdraw Period"); ?></th>
                                                <th scope="col"><?= $vw ?></th>
                                                <th scope="col"><?= $et ?></th>
                                                <th scope="col"><?= $dl ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // psr = pending stockvell result
                                            // $psr_result - getting from dashboard.inc.php
                                            $leader_detail = null;
                                            foreach ($aasr_result as $aasr_key) {
                                                if ($aasr_key["leader_id"] || $aasr_key["leader_id"] === '' || $aasr_key["leader_id"] === null) {
                                                    $leader_detail = $member_controler->findMemberByID($aasr_key["leader_id"], '/admin');
                                                }
                                                $leader_name = "";
                                                if($leader_detail){
                                                    $leader_name .= $leader_detail->firstname . " " . $leader_detail->surname;
                                                }
                                                echo "
                                                   <tr class='text-capitalize'>
                                                      <th>" . $aasr_key["id"] . "</th>
                                                      <td>" . $aasr_key["name"] . "</td>
                                                      <td>" . $aasr_key["category"] . "</td>
                                                      <td>" . $aasr_key["payment"] . "</td>
                                                      <td>" . $aasr_key["currency"] . "</td>
                                                      <td>" . $aasr_key["payment_frequency"] . "</td>
                                                      <td>$leader_name</td>
                                                      <td>" . $with_per->convertFromIntToText($aasr_key["withdraw_frequency"]) . "</td>
                                                      <td><a href='/pack_single/?stockvel_id=" . $aasr_key["id"] . "' class='btn btn-primary'>$vw</a></td>
                                                      <td><a href='/edit_stockvell/?stockvel_id=" . $aasr_key["id"] . "' class='btn btn-primary'>$et</a></td>
                                                      <td><a href='/admin/?stockvel_id=" . $aasr_key["id"] . "' class='btn btn-primary' >$dl</a></td>
                                                   </tr>
                                                ";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }
                            }
                            ?>
                        </div>
                        <!--
                           AAPPROVED PACK CONTENT END
                           =================================================================================================
                        -->

                        <!--
                           PENDING PACK CONTENT START
                           =================================================================================================
                        -->
                        <div class="content pending-pack-content d-none">
                            <?php
                            if (isset($single_stockvel_id)) {
                                $rpl_result = $stockvell_pack->allMembersWhoRequestToBeLeader($single_stockvel_id); // rpl = request pack leaders
                                $ssr_result = $stockvell_pack->getASingleApprovedStockvell($single_stockvel_id); // ssr = single stockvell result
                                $leader = $member_controler->findMemberByID($ssr_result['leader_id'], '/admin');

                                $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $single_stockvel_id);

                                ?>
                                <div class="row">
                                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                                    <p><?php echo $ssr_result['description']; ?></p>
                                    <p><?= $ssr_result['category']; ?></p>
                                    <p> <?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                                    <?php
                                    $lr = __('Leader');
                                    $sl = __('Suspend Leader');
                                    $leader_name ="";
                                    if($leader){
                                        $leader_name .= $leader->firstname . " " . $leader->surname;
                                    }
                                    echo "<div class='d-flex'> 
                                             <p>
                                                $lr $leader_name
                                             </p>
                                             <form action='/includes/admin.inc.php' method='post'>
                                                $stockvell_id_hidden_input
                                                <button type='submit' name='suspend_leader_submit' class='btn btn-danger mx-3'>$sl</button>
                                             </form>
                                          </div>";
                                    ?>
                                </div>
                                <!--
                                Leader request starts
                                ========================================================================================
                                -->
                                <div class="row">
                                    <p>
                                    <h2 class="h2" data-bs-toggle="collapse" href="#leaderCollapse" role="button"
                                        aria-expanded="false" aria-controls="leaderCollapse"><span><img
                                                    src="/public/icons/down-arrow.svg" height="30"
                                                    alt=""></span> <?= __("Members requested to be a leader") ?></h2>
                                    </p>
                                    <div class="collapse" id="leaderCollapse">
                                        <?php
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
                                                    // rpl = requestd pack leader

                                                    foreach ($rpl_result as $rpl_key) {


                                                        $member_id_hidden_input = $input_field->inputHidden("member_id", $rpl_key["member_id"]);
                                                        $sm_id_hidden_input = $input_field->inputHidden("sm_id", $rpl_key["id"]);
                                                        echo "
                                          <tr class='text-capitalize'>
                                             <th>" . $rpl_key["id"] . "</th>
                                             <td>" . $rpl_key["firstname"] . " " . $rpl_key["surname"] . "</td>
                                             <td class='text-lowercase'>" . $rpl_key["email"] . "</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["govt_id_proof"] . "'>View</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["address_proof"] . "'>View</td>
                                             <td>
                                             <form action='/includes/admin.inc.php' method='post'>
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
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--
                                Leader request ends
                                ========================================================================================
                                -->
                                <?php
                            } else {
                                ?>
                                <!--
                                Pending pack details start
                                ========================================================================================
                                -->
                                <h1 class="h1 text-center"><?= __("All pending packs!"); ?></h1>
                                <p class="text-center"><?= __("You can find all the stockvel packs that are made or requested for approval. If you cannot find a pack that you have created, the pack has been rejected. Try creating another one with proper information!"); ?></p>
                                <?php
                                // psr = pending stockvell result
                                // $apsr_result - getting from dashboard.inc.php

                                if (count($apsr_result) <= 0) {
                                    echo "<div class='alert alert-warning'>$npf</div>";
                                } else { ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-warning">
                                            <thead>
                                            <colgroup span="8"></colgroup>
                                            <colgroup span="4"></colgroup>
                                            <tr class="bg-warning text-white border-primary">
                                                <th colspan="8"><?= __("Pack Details") ?></th>
                                                <th colspan="4"><?= __("Action"); ?></th>
                                            </tr>
                                            <tr class="bg-warning text-white border-primary">
                                                <th scope="col">#<?= __("ID"); ?></th>
                                                <th scope="col"><?= __("Name"); ?></th>
                                                <th scope="col"><?= __("Category"); ?></th>
                                                <th scope="col"><?= __("Payment"); ?></th>
                                                <th scope="col"><?= __("Currency"); ?></th>
                                                <th scope="col"><?= __("Payment Period"); ?></th>
                                                <th scope="col"><?= __("Withdraw Period"); ?></th>
                                                <th scope="col"><?= __("Previlage"); ?></th>
                                                <th scope="col"><?= __("Edit"); ?></th>
                                                <th scope="col"><?= __("Detail"); ?></th>
                                                <th scope="col"><?= __("Reject"); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // psr = pending stockvell result
                                            // $apsr_result - getting from dashboard.inc.php
                                            foreach ($apsr_result as $apsr_key) {
                                                # code...
                                                echo "
                                       <tr class='text-capitalize'>
                                          <th>" . $apsr_key["id"] . "</th>
                                          <td>" . $apsr_key["name"] . "</td>
                                          <td>" . $apsr_key["category"] . "</td>
                                          <td>" . $apsr_key["payment"] . "</td>
                                          <td>" . $apsr_key["currency"] . "</td>
                                          <td>" . $apsr_key["payment_frequency"] . "</td>
                                          <td>" . $with_per->convertFromIntToText($apsr_key["withdraw_frequency"]) . "</td>
                                          <td>
                                             <form class='p-0 m-0' action='/includes/admin.inc.php?stockvel_id=" . $apsr_key["id"] . "&leader_id=" . $apsr_key["leader_id"] . "' method='post'>
                                             <button type='submit' name='approve_stockvell_pack' class='btn btn-primary'>$av</button>  
                                             </form>
                                          </td>
                                          <td><a href='/edit_stockvell/?stockvel_id=" . $apsr_key["id"] . "' class='btn btn-warning text-white'>Edit</a></td>
                                          <td><a href='/admin/?stockvel_id=" . $apsr_key["id"] . "' class='btn btn-primary' >$dl</a></td>
                                          <td>
                                             <form class='p-0 m-0' action='/includes/admin.inc.php?stockvel_id=" . $apsr_key["id"] . "' method='post'>
                                             <button type='submit' name='reject_stockvell_pack' class='btn btn-danger'>$rj</button>  
                                             </form>
                                          </td>
                                       </tr>
                                    ";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }
                                // <!--
                                // Pending pack details end
                                // ========================================================================================
                                //  -->
                            }
                            ?>
                        </div>
                        <!--
                           PENDING PACK CONTENT END
                           =================================================================================================
                        -->

                        <!--
                           CLOSE PACK CONTENT START
                           =================================================================================================
                        -->
                        <div class="content close-pack-content d-none">
                            <?php
                            if (isset($single_stockvel_id)) {
                                $rpl_result = $stockvell_pack->allMembersWhoRequestToBeLeader($single_stockvel_id); // rpl = request pack leaders
                                $ssr_result = $stockvell_pack->getASingleApprovedStockvell($single_stockvel_id); // ssr = single stockvell result
                                $leader = $member_controler->findMemberByID($ssr_result['leader_id'], '/admin');

                                $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $single_stockvel_id);
                                ?>
                                <div class="row">
                                    <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                                    <p><?php echo $ssr_result['description']; ?></p>
                                    <p><?= $ssr_result['category']; ?></p>
                                    <p> <?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                                    <?php if ($leader) {
                                        $lr = __('Leader');
                                        $sl = __('Suspend Leader');
                                        echo "<div class='d-flex'> 
                                 <p>
                                    $lr $leader->firstname  $leader->surname
                                 </p>
                                 <form action='/includes/admin.inc.php' method='post'>
                                    $stockvell_id_hidden_input
                                    <button type='submit' name='suspend_leader_submit' class='btn btn-danger mx-3'>$sl</button>
                                 </form>
                              </div>";
                                    } ?>
                                </div>
                                <!-- single stockvell detail starts  -->
                                <div class="row">
                                    <p>
                                    <h2 class="h2" data-bs-toggle="collapse" href="#leaderCollapse" role="button"
                                        aria-expanded="false" aria-controls="leaderCollapse"><span><img
                                                    src="/public/icons/down-arrow.svg" height="30"
                                                    alt=""></span> <?= __("Members requested to be a leader") ?></h2>
                                    </p>
                                    <div class="collapse" id="leaderCollapse">
                                        <?php
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
                                                    // rpl = requestd pack leader

                                                    foreach ($rpl_result as $rpl_key) {


                                                        $member_id_hidden_input = $input_field->inputHidden("member_id", $rpl_key["member_id"]);
                                                        $sm_id_hidden_input = $input_field->inputHidden("sm_id", $rpl_key["id"]);
                                                        echo "
                                          <tr class='text-capitalize'>
                                             <th>" . $rpl_key["id"] . "</th>
                                             <td>" . $rpl_key["firstname"] . " " . $rpl_key["surname"] . "</td>
                                             <td>" . $rpl_key["email"] . "</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["govt_id_proof"] . "'>View</td>
                                             <td><a class='btn btn-primary' href='/uploads/" . $rpl_key["address_proof"] . "'>View</td>
                                             <td>
                                             <form action='/includes/admin.inc.php' method='post'>
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
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- single stockvell detail ends  -->
                                <?php
                            } else {
                                ?>
                                <!-- // Pending pack details start  -->
                                <h1 class="h1 text-center"><?= __("All close requested packs!"); ?></h1>
                                <p class="text-center"><?= __("All packs are completed and the leader requested to close the pack."); ?></p>
                                <?php
                                // psr = pending stockvell result
                                // $acrsr_result - getting from dashboard.inc.php
                                // var_dump($acrsr_result);

                                if (count($acrsr_result) <= 0) {
                                    echo "<div class='alert alert-warning'>$npf</div>";
                                } else { ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-warning">
                                            <thead>
                                            <colgroup span="8"></colgroup>
                                            <colgroup span="4"></colgroup>
                                            <tr class="bg-warning text-white border-primary">
                                                <th colspan="8"><?= __("Pack Details"); ?></th>
                                                <th colspan="4"><?= __("Action"); ?></th>
                                            </tr>
                                            <tr class="bg-warning text-white border-primary">
                                                <th scope="col">#<?= __("ID"); ?></th>
                                                <th scope="col"><?= __("Name"); ?></th>
                                                <th scope="col"><?= __("Category"); ?></th>
                                                <th scope="col"><?= __("Payment"); ?></th>
                                                <th scope="col"><?= __("Currency"); ?></th>
                                                <th scope="col"><?= __("Payment Period"); ?></th>
                                                <th scope="col"><?= __("Withdraw Period"); ?></th>
                                                <th scope="col"><?= __("Close"); ?></th>
                                                <th scope="col"><?= __("Edit"); ?></th>
                                                <th scope="col"><?= __("Detail"); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            // psr = pending stockvell result
                                            // $acrsr_result - getting from dashboard.inc.php
                                            foreach ($acrsr_result as $acrsr_key) {
                                                # code...
                                                echo "
                                       <tr class='text-capitalize'>
                                          <th>" . $acrsr_key["id"] . "</th>
                                          <td>" . $acrsr_key["name"] . "</td>
                                          <td>" . $acrsr_key["category"] . "</td>
                                          <td>" . $acrsr_key["payment"] . "</td>
                                          <td>" . $acrsr_key["currency"] . "</td>
                                          <td>" . $acrsr_key["payment_frequency"] . "</td>
                                          <td>" . $with_per->convertFromIntToText($acrsr_key["withdraw_frequency"]) . "</td>
                                          <td>
                                             <form class='p-0 m-0' action='/includes/admin.inc.php?stockvel_id=" . $acrsr_key["id"] . "&leader_id=" . $acrsr_key["leader_id"] . "' method='post'>
                                             <button type='submit' name='close_stockvell_pack' class='btn btn-danger'>$cl</button>  
                                             </form>
                                          </td>
                                          <td><a href='/edit_stockvell/?stockvel_id=" . $acrsr_key["id"] . "' class='btn btn-warning text-white'>Edit</a></td>
                                          <td><a href='/admin/?stockvel_id=" . $acrsr_key["id"] . "' class='btn btn-primary' >$dl</a></td>
                                       </tr>
                                    ";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }
                                // <!-- // Pending pack details end  -->
                            }
                            ?>
                        </div>
                        <!--
                           CLOSE PACK CONTENT END
                           =================================================================================================
                        -->


                        <!--
                           ADD PACK CONTENT START
                           =================================================================================================
                        -->
                        <div class="content add-pack-content d-none my-4">
                            <h1 class="h1 text-center"><?= __("Add pack!"); ?>!</h1>
                            <p class="text-center"><?= __("This pack will be added as a pending pack that will don't be available to everyone until the admin approves it."); ?>
                                !</p>

                            <?php
                            $nm = __("Name*");
                            $pyt = __("Payment*");
                            $pytf = __("Payment Frequency");
                            $wdf = __("Withdraw Frequency");
                            $ct = __("Category*");
                            $dsc = __("Description*");
                            $ccc = __("Currency") . "*";
                            $sa = __("Start at") . "*";
                            $ea = __("End at") . "*";
                            $agmt = __("You Must Write Agreement About This Stockvel Pack*");
                            ?>
                            <!-- Form start  -->
                            <form action="/includes/admin.inc.php" method="POST">
                                <div class="row mb-3">
                                    <?php // echo inputElement('name', 'Name*', false, 'text');
                                    echo $input_field->inputText("name", $nm, true, "text", true);
                                    ?>

                                </div>
                                <div class="row mb-3">
                                    <?php
                                    echo $input_field->inputText('payment', $pyt, false, 'number', true);
                                    echo $input_field->inputSelect('currency', $ccc, false, strtoupper('XOF (CFA)'), $currency_short);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    // echo $input_field->inputSelect("payment_frequency", $wdf, false, 'Weekly', $freq_days);
                                    echo $input_field->inputSelect("payment_frequency", $pytf, false, 'Weekly', $with_freq);
                                    echo $input_field->inputSelect("withdraw_frequency", $wdf, false, 'Weekly', $with_freq);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    echo $input_field->inputSelect("category", $ct, false, null, $category_list);
                                    echo $input_field->inputText("max_member", "Total Member Limit", false, 'number', true);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    echo $input_field->inputDate('start_at', $sa, false, true);
                                    echo $input_field->inputDate('end_at', $ea, false, true);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    $desc_tooltip = "Describe your pack, what is the goal of your pack, and more.";
                                    echo $input_field->inputTextarea("description", $dsc, true, true, null, 2, null, null, $desc_tooltip);
                                    ?>
                                </div>
                                <div class="row mb-3">
                                    <?php
                                    $html_msg = new HTMLMessage();
                                    $default_agreement = $html_msg->agreementDefault();
                                    echo $input_field->inputTextarea("agreement", $agmt, true, true, $default_agreement);
                                    ?>
                                </div>


                                <button type="submit" name="create_stockvell_pack"
                                        class="btn btn-primary"><?= __("Create Stockvel") ?></button>
                            </form>
                            <!-- Form end  -->
                        </div>
                        <!--
                           ADD PACK CONTENT END
                           =================================================================================================
                        -->
                    </div>
                </div>
            </section>
            <!-- Authenticated content end  -->
            <?php
        } else {
            ?>
            <!-- unauthenticated content start  -->
            <section class="section-1">
                <div class="container">
                    <div class="admin-login-caption text-center">
                        <h1 class="h1 text-center text-capitalize"><?= __("Login as Admin"); ?></h1>
                        <p class="text-center"><?= __("Please enter the followings"); ?></p>
                    </div>
                    <?php if ($has_error) echo $err_handler->displayErrors(); ?>
                    <form action="/includes/admin.inc.php" method="POST">
                        <div class="row mb-3">
                            <?php
                            $password = __("Password");
                            echo $input_field->inputText("email", "Email", false, "email", true);
                            echo $input_field->inputText("password", $password, false, "password", true);
                            ?>
                        </div>
                        <button type="submit" name="login_admin_submit"
                                class="btn btn-primary"><?= __("Login"); ?></button>
                    </form>
                </div>
            </section>
            <!-- unauthenticated content end  -->
            <?php
        }
        ?>
    </main>

<?php require_once("./layouts/footer.php"); ?>