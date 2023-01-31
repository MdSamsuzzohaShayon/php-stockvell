<?php
session_start(); // In every single page we should start our session at the top of our code

if (!isset($_SESSION['member_id'])) {
   header("Location: /login.php");
   exit();
}
if (isset($_SESSION['admin_id'])) {
   header("Location: /admin.php");
   exit();
}
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$SITE_URL = "http://stockvell.allinone-office.com";


$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];

$single_stockvel_id = $_GET["stockvel_id"];


require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . '/layouts/header.php');
// Check for session
require_once($ROOT . "/includes/dashboard.inc.php");
require_once($ROOT . "/config/option-list.php");
// require_once($ROOT . "/utils/input-fields.php");
// require_once($ROOT . "/classes/input.classes.php");

use Utils\ErrorHandler;
use Utils\InputField;
use Utils\PeriodConvert;
use Utils\HTMLMessage;
use Models\Member\FetchMember;
use Models\Stockvell\FetchStockvell;


$stockvell_control = new FetchStockvell(); // Variables getting from dashboard.php
$stockvell_control->setMember($member_email, $member_id);
$cmr_result = $stockvell_control->getCurrentMember(); // cm = current member result
$psr_result = $stockvell_control->getAllPendingStockvellOfAMember("PENDING", $member_id); // psr = pending search result
$asr_result = $stockvell_control->getAllApprovedStockvellOfAMember($member_id); // asr = approved search result
$csr_result = $stockvell_control->getAllCoseStockvellOfAMember($member_id); // csr = close search result

$member_controler = new FetchMember();

$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
   $has_error = true;
   $err_handler->setCommonErrors($_GET["error"]);
}

$input_field = new InputField();
$with_per = new PeriodConvert();


$dl = __("Detail");
$lr = __('Leader');
$sl = __('Resign Leadership');
$cr = __('Close Request');
$gl = __('Generate Link');
$npf =__("No pack found");

?>



<main class="dashboard">

   <section class="section-1">
      <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
         <!-- Sidebar menu start  -->
         <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
            <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
               <li role="button" data-item="profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                  <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Profile"); ?></p>
               </li>
               <li role="button" data-item="my-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                  <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("My Pack"); ?></p>
               </li>
               <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Pending Pack"); ?></p>
               </li>
               <li role="button" data-item="close-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/close-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Closed Packs"); ?></p>
               </li>
               <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Add Pack"); ?></p>
               </li>
            </ul>
         </div>
         <!-- Sidebar menu end  -->

         <!-- sidebar content start  -->
         <div class="col-md-9 sidebar-content">

            <!-- profile content start  -->
            <div class="content my-4 profile-content d-block">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Update your information!") ?></h1>
                  <p><?= __("Edit any details of a Stokvel pack!") ?></p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <form action="/includes/dashboard.inc.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3 mx-0">
                     <?php
                     $fn = __('Firstname*');
                     $sn = __('Surname*');
                     $el = __('Email*');
                     $pw = __('Password*');
                     $cpw = __('Confirm Password*');
                     $cy = __('Country*');
                     $cty = __('City*');
                     $pn = __('Phone*');
                     $gr = __('Select Gender*');
                     $pro = __('Profession*');
                     $ist = __("Interest (Optional comma-separated list)");
                     $gid = __("Government ID*");
                     $src = __('How did you hear about the Stockvel platform? (Optional)');

                     echo $input_field->inputText("firstname", $fn, false, "text", false, $cmr_result->firstname);
                     echo $input_field->inputText("surname", $sn, false, "text", false, $cmr_result->surname);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("email", $el, true, "email", false, $cmr_result->email);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("password", $pw, false, "password", false);
                     echo $input_field->inputText("password2", $pw, false, "password", false);
                     ?>

                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     // echo $cmr_result->country;
                     $county_proper = trim(explode('(', $cmr_result->country)[0]);
                     echo $input_field->inputSelect("country", $cy, false, $county_proper, $country_list);
                     echo $input_field->inputPhone("phone", $pn, false, true, $country_code_list, $cmr_result->phone);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("city", $cty, false, "text", false, $cmr_result->city);
                     echo $input_field->inputSelect("gender", $gr, false, $cmr_result->gender, ["male", "female", "others"]);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("profession", $pro, false, $cmr_result->profession, $professions);
                     echo $input_field->inputFile("govt_id", $gid, false);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("interest", $ist, true, false, $cmr_result->interest);
                     ?>
                  </div>


                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("source", $src, true, false, $cmr_result->source);
                     ?>
                  </div>

                  <button type="submit" name="member_update_submit" class="btn btn-primary"><?= __("Update"); ?></button>
               </form>
            </div>
            <!-- profile content end  -->

            <!-- my pack content start  -->
            <div class="content my-pack-content d-none">
               <?php
               if (isset($single_stockvel_id)) {
                  $ssr_result = $stockvell_control->getASingleApprovedStockvell($single_stockvel_id); // ssr = single stockvell result
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
                  if (empty($ssr_result['id'])) {
                     header("Location: /packs");
                     exit();
                  }

               ?>
                  <!-- stockvell detail start  -->
                  <div class="row mx-0 mb-3">
                     <?php
                     if ($ssr_result['status'] === "PENDING") {
                        $aps = __("This pack is not approved yet");
                        echo '<p class="alert alert-danger">' . $aps . '</p>';
                     }
                     ?>
                     <h1 class="h1"><?= $ssr_result['name']; ?></h1>

                     <div class="card p-0">
                        <div class="card-header">
                           <?= $ssr_result['category']; ?>
                        </div>
                        <div class="card-body mb-3">
                           <h5 class="card-title"><?= $ssr_result['category']; ?></h5>
                           <p class="card-text"><?php echo $ssr_result['description']; ?></p>
                           <p class="card-text"><?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                           <?php if ($leader && $leader->id === $member_id) {
                              echo "<div class='d-flex flex-column'>
                                       <p class='card-text'>
                                          $lr $leader->firstname  $leader->surname
                                       </p>
                                       <form action='/includes/admin.inc.php' method='post'>
                                          $stockvell_id_hidden_input
                                          <button type='submit' name='suspend_leader_submit' class='btn btn-primary'>$sl</button>
                                       </form>
                                    </div>";
                           } ?>
                        </div>
                     </div>
                  </div>
                  <?php
                  $withdraw_member = null;
                  if ($leader && $leader->id === $member_id) {
                  ?>
                     <!-- widthdraw member selection start -->
                     <div class="row mb-3 mx-0">
                        <div class="card p-0">
                           <div class="card-header">
                              <?= __("Withdraw member"); ?>
                           </div>
                           <div class="card-body">
                              <form action="/includes/dashboard.inc.php" method="post">
                                 <?php
                                 echo $stockvell_id_hidden_input;
                                 ?>
                                 <div class="row mx-0 mb-3">
                                    <label for="withdraw_member_id">Select Withdraw Member</label>
                                    <select name="withdraw_member_id" id="withdraw_member_id" class="form-control">
                                       <?php
                                       foreach ($approved_members as $member) {
                                          // array_push($member_list, $member);
                                          // echo $member['firstname'];
                                          $withdraw_member_id = $member['id'];
                                          $member_name = $member['firstname'] . " " . $member['surname'];
                                          // echo intval($ssr_result['withdraw_member_id']) . "===" . intval($withdraw_member_id) . "<br />"; 
                                          if (intval($ssr_result['withdraw_member_id']) === intval($withdraw_member_id)) {
                                             $withdraw_member = $member;
                                             echo "<option selected value='$withdraw_member_id'>$member_name</option>";
                                          } else {
                                             echo "<option value='$withdraw_member_id'>$member_name</option>";
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                                 <button type="submit" class="btn btn-primary" name="withdraw_member_submit"><?= __('Withdraw member') ?></button>
                              </form>
                              <?php
                              if ($withdraw_member) {
                                 // var_dump($withdraw_member);
                                 $withdraw_member_name = $withdraw_member["firstname"] . " " . $withdraw_member["surname"];
                                 echo "<h5 class='card-title'>Withdraw member name: $withdraw_member_name </h3>";
                              }
                              // var_dump($withdraw_member)
                              ?>
                           </div>
                        </div>
                     </div>
                     <!-- widthdraw member selection end -->


                     <!-- approved members start  -->
                     <div class="row mb-3">
                        <p>
                        <h2 class="h2" data-bs-toggle="collapse" href="#approvedMemberCollapse" role="button" aria-expanded="false" aria-controls="approvedMemberCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Members") ?></h2>
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
                           <?php }                  ?>
                        </div>
                     </div>
                     <!-- approved members end  -->

                     <!-- Pending members start  -->
                     <div class="row mb-3">
                        <p>
                        <h2 class="h2" data-bs-toggle="collapse" href="#unapprovedMemberCollapse" role="button" aria-expanded="false" aria-controls="unapprovedMemberCollapse"> <span><img src="/public/icons/down-arrow.svg" height="30" alt=""></span> <?= __("Member requests") ?></h2>
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
                     <!-- Pending members end  -->

                  <?php
                  }
                  ?>
                  <!-- action one section start  -->
                  <div class="row mb-3 mx-0">
                     <div class="card p-0">
                        <div class="card-header">
                           <?= __("Make a link to share this pack with public"); ?>
                        </div>
                        <div class="card-body">
                           <?php
                           if ($ssr_result['link']) {
                              $new_link = $SITE_URL . "/single_pack/stockvel_id=" . $single_stockvel_id . "&sharing=" . $ssr_result['link'];
                              echo '<p class="card-text alert alert-primary"> ' . $new_link . ' </p>';
                           }else{
                              $new_link = $SITE_URL . "/single_pack/stockvel_id=" . $single_stockvel_id;
                              echo "<div class='alert alert-primary' id='generated-link-display'> $new_link </div>";
                           }
                           // echo "Link - " . $new_link . "<br />";
                           ?>
                           <div class="d-flex justify-content-start">
                              <?php
                              if ($leader && $leader->id === $member_id) {
                                 $leader_id_hidden_input = $input_field->inputHidden("leader_id", $member_id);
                                 echo "
                                       <form action='/includes/dashboard.inc.php' method='post'>
                                          $stockvell_id_hidden_input
                                          $leader_id_hidden_input
                                          <button type='submit' class='btn mx-2 btn-primary' id='generate-link' name='stockvell_generate_link_submit' >$gl</button>
                                       </form>
                                       ";
                              }
                              ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- action one section end  -->

                  <!-- action two section start  -->
                  <!-- <div class="row mb-3 mx-0">
                     <div class="card p-0">
                        <div class="card-header">
                           <?= __("A tool for leader and members meeting"); ?>
                        </div>
                        <div class="card-body">
                           <div class="d-flex justify-content-start">
                              <?php
                              if ($leader && $leader->id === $member_id) {
                                 $leader_id_hidden_input = $input_field->inputHidden("leader_id", $member_id);
                                 echo "
                                       <form action='/includes/dashboard.inc.php' method='post'>
                                          $stockvell_id_hidden_input
                                          $leader_id_hidden_input
                                          <button type='submit' class='btn mx-2 btn-primary' id='meet-link' name='stockvell_meet_link_submit' >Add Meet Link</button>
                                       </form>
                                       ";
                              }
                              ?>
                           </div>
                        </div>
                     </div>
                  </div> -->
                  <!-- action two section end  -->

                  <!-- action three section start  -->
                  <div class="row mb-3 mx-0 d-flex">
                     <?php
                     if ($leader && $leader->id === $member_id) {
                        $leader_id_hidden_input = $input_field->inputHidden("leader_id", $member_id);
                        echo "
                                       <form action='/includes/dashboard.inc.php' method='post' class='w-fit'>
                                          $stockvell_id_hidden_input
                                          $leader_id_hidden_input
                                          <button type='submit' class='btn mr-2 btn-danger w-fit' name='stockvell_close_request_submit'>$cr</button>
                                       </form>
                                       ";
                     }
                     ?>
                     <a href="/dashboard" class="btn mx-2 btn-primary w-fit"><?= __("Back to the list"); ?></a>
                  </div>
                  <!-- action three section end  -->
                  <!-- stockvell detail end  -->
               <?php
               } else {
               ?>
                  <!-- my packlist start  -->
                  <h1 class="h1 text-center"><?= __("My pack") ?></h1>
                  <p class="text-center"><?= __("All the packs that you are a member of.") ?></p>
                  <?php
                  $asr_psr_result = array_merge($asr_result, $psr_result); // pending and approved pack
                  // var_dump($asr_psr_result);
                  if (count($asr_psr_result) <= 0) {
                     $npf = __('No pack found');
                     echo "<div class='alert alert-warning'>$npf</div>";
                  } else { ?>
                     <div class="table-responsive">
                        <table class="table table-bordered border-warning">
                           <thead class="bg-warning text-white border-primary">
                              <tr>
                                 <th scope="col">#<?= __("ID") ?></th>
                                 <th scope="col"><?= __("Name") ?></th>
                                 <th scope="col"><?= __("Category") ?></th>
                                 <th scope="col"><?= __("Status") ?></th>
                                 <th scope="col"><?= __("Payment") ?></th>
                                 <th scope="col"><?= __("Payment Period") ?></th>
                                 <th scope="col"><?= __("Withdraw Period") ?></th>
                                 <th scope="col"><?= __("Detail") ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // psr = pending stockvell result
                              // $asr_psr_result - getting from dashboard.inc.php
                              if (count($asr_psr_result) <= 0) {
                                 echo "<div class='alert alert-warning'>$npf</div>";
                              } else {
                                 foreach ($asr_psr_result as $asr_psr_key) {
                                    # code...
                                    echo "
                                 <tr class='text-capitalize'>
                                    <th>" . $asr_psr_key["id"] . "</th>
                                    <td>" . $asr_psr_key["name"] . "</td>
                                    <td>" . $asr_psr_key["category"] . "</td>
                                    <td>" . $asr_psr_key["status"] . "</td>
                                    <td>" . $asr_psr_key["payment"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($asr_psr_key["payment_frequency"]) . "</td>
                                    <td>" . $with_per->convertFromIntToText($asr_psr_key["withdraw_frequency"]) . "</td>
                                    <td><a href='/dashboard/?stockvel_id=" . $asr_psr_key["id"] . "' class='btn btn-warning'>$dl</a></td>
                                 </tr>
                              ";
                                 }
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  <?php }               ?>
                  <!-- my packlist end  -->
               <?php
               }
               ?>
            </div>
            <!-- my pack content end  -->

            <!-- pending content start  -->
            <div class="content pending-pack-content d-none">
               <h1 class="h1"><?= __("Pending packs!") ?></h1>
               <p><?= __("You can find all the stockvel packs that are made or requested for approval. If you cannot find a pack that you have created, the pack has been rejected. Try creating another one with proper information!") ?></p>
               <?php
               // psr = pending stockvell result
               // $psr_result - getting from dashboard.inc.php
               if (count($psr_result) <= 0) {
                  echo "<div class='alert alert-warning'>$npf</div>";
               } else { ?>
                  <div class="table-responsive">
                     <table class="table table-bordered border-warning">
                        <thead class="bg-warning text-white border-primary">
                           <tr>
                              <th scope="col">#<?= __("ID"); ?></th>
                              <th scope="col"><?= __("Name"); ?></th>
                              <th scope="col"><?= __("Category"); ?></th>
                              <th scope="col"><?= __("Status"); ?></th>
                              <th scope="col"><?= __("Payment"); ?></th>
                              <th scope="col"><?= __("Currency"); ?></th>
                              <th scope="col"><?= __("Payment Period"); ?></th>
                              <th scope="col"><?= __("Withdraw Period"); ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           // psr = pending stockvell result
                           // $psr_result - getting from dashboard.inc.php
                           if (count($psr_result) <= 0) {
                              echo "<div class='alert alert-warning'>$npf</div>";
                           } else {
                              foreach ($psr_result as $psr_key) {
                                 # code...
                                 echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $psr_key["id"] . "</th>
                                    <td>" . $psr_key["name"] . "</td>
                                    <td>" . $psr_key["category"] . "</td>
                                    <td>" . $psr_key["status"] . "</td>
                                    <td>" . $psr_key["payment"] . "</td>
                                    <td>" . $psr_key["currency"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["payment_frequency"]) . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["withdraw_frequency"]) . "</td>
                                 </tr>
                              ";
                              }
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               <?php }                ?>

            </div>
            <!-- pending content end  -->

            <!-- closed content start  -->
            <div class="content close-pack-content d-none">
               <h1 class="h1"><?= __("Closed Packs!") ?></h1>
               <p><?= __("You can find all the Stockvel packs that are closed or requested for closing. If you cannot find a pack that you have created, the pack has been rejected. Try creating another one with the proper information!") ?></p>
               <?php
               // psr = pending stockvell result
               // $psr_result - getting from dashboard.inc.php
               if (count($csr_result) <= 0) {
                  echo "<div class='alert alert-warning'>$npf</div>";
               } else { ?>
                  <div class="table-responsive">
                     <table class="table table-bordered border-warning">
                        <thead class="bg-warning text-white border-primary">
                           <tr>
                              <th scope="col">#ID</th>
                              <th scope="col">Name</th>
                              <th scope="col">Category</th>
                              <th scope="col">Status</th>
                              <th scope="col">Payment</th>
                              <th scope="col">Currency</th>
                              <th scope="col">Payment Period</th>
                              <th scope="col">Withdraw Period</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           // psr = pending stockvell result
                           // $csr_result - getting from dashboard.inc.php
                           if (count($csr_result) <= 0) {
                              echo "<div class='alert alert-warning'>$npf</div>";
                           } else {
                              foreach ($csr_result as $psr_key) {
                                 # code...
                                 echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $psr_key["id"] . "</th>
                                    <td>" . $psr_key["name"] . "</td>
                                    <td>" . $psr_key["category"] . "</td>
                                    <td>" . $psr_key["status"] . "</td>
                                    <td>" . $psr_key["payment"] . "</td>
                                    <td>" . $psr_key["currency"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["payment_frequency"]) . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["withdraw_frequency"]) . "</td>
                                 </tr>
                              ";
                              }
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               <?php }                ?>

            </div>
            <!-- closed content end  -->

            <!-- add pack content start -->
            <div class="content add-pack-content d-none my-4">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Create your own pack!") ?></h1>
                  <p><?= __("You can create your own pack. In order to do that you need to fill in all the input fields. All new pack needs to be reviewed.") ?></p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <!-- Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3 mx-0">
                     <?php // echo inputElement('name', 'Name*', false, 'text');
                     $nm = __("Name*");
                     $pyt = __("Payment*");
                     $ccc = __("Currency") . "*";
                     $pytf = __("Payment Frequency*");
                     $wdf = __("Withdraw Frequency*");
                     $ct = __("Category") . "*";
                     $ap = __("Proof of address (JPG, PNG, PDF)");
                     $dc = __("Description");
                     $sa = __("Start at") . "*";
                     $ea = __("End at") . "*";
                     $gid = __("Govt ID Proof (JPG, PNG, PDF)");
                     $agmt = __("You Must Write Agreement About This Stockvel Pack*");
                     echo $input_field->inputText("name", $nm, true, "text", true);
                     ?>

                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText('payment', $pyt, false, 'number', true);
                     echo $input_field->inputSelect('currency', $ccc, false, strtoupper('XOF (CFA)'), $currency_short);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("payment_frequency",  $pytf, false, 'Weekly', $with_freq);
                     // echo $input_field->inputSelect("payment_frequency",  $wdf, false, 'Weekly', $with_freq);
                     echo $input_field->inputSelect("withdraw_frequency", $wdf, false, 'Weekly', $with_freq);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("category", $ct, false, null, $category_list);
                     echo $input_field->inputText("max_member", "Total Member Limit", false, 'number', true);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputDate('start_at', $sa, false, true);
                     echo $input_field->inputDate('end_at', $ea, false, true);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("description", $dc, true, true);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     $arg_str = new HTMLMessage();
                     // $default_agreement = __("<h2>The&nbsp;goal&nbsp;of&nbsp;the&nbsp;pack&nbsp;</h2><p><strong>Rules&nbsp;and&nbsp;regulations</strong>&nbsp;</p><ul><li>rule1&nbsp;</li><li>rule2&nbsp;</li><li>rule3&nbsp;</li><li>rule4&nbsp;</li><li>rule5</li></ul>");
                     $leader_name = $cmr_result->firstname . " " . $cmr_result->surname;
                     $default_agreement = $arg_str->agreementDefault($leader_name);
                     echo $input_field->inputTextarea("agreement", $agmt, true, true, $default_agreement);
                     ?>
                  </div>

                  <div class="row mx-0">
                     <hr>
                     <p><?= __("You need to submit all proof because once you create a stockvel pack by default you will request to be the leader of the group. We will check your papers, if everything is okay we will accept your pack!") ?></p>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputFile("address_proof", $ap, false, true);
                     echo $input_field->inputFile("govt_id_proof", $gid, false, true);
                     ?>
                  </div>
                  <?php
                  echo $input_field->inputHidden("member_id", $member_id)
                  ?>
                  <div class="row mb-3 mx-0">
                     <button type="submit" name="create_stockvell_pack" class="btn btn-primary w-fit"><?= __("Create Stockvel") ?></button>
                  </div>
               </form>
               <!-- Form end  -->
            </div>
            <!-- add pack content end -->

         </div>
         <!-- sidebar content end  -->
      </div>
   </section>
</main>

<?php require_once("./layouts/footer.php"); ?>