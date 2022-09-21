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
$apsr_result = $stockvell_pack->getAllPendingStockvell("PENDING", $is_admin, null); // apsr = all pending stockvell result
$aasr_result = $stockvell_pack->getStockvellByStatus('APPROVED'); // aasr = all approved stockvell result
$amr_result = $stockvell_pack->getAllMembers($is_admin);

$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
   $has_error = true;
   $err_handler->setCommonErrors($_GET["error"]);
}


$input_field = new InputField();


$av = __("Approve");
$vw = __("View");
$vd = __("Verified");
$et = __("Edit");
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
            <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
               <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
                  <li role="button" data-item="admin-profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                     <img class="mx-md-4 mx-0" height="33" src="/public/icons/profile-icon.svg" alt="">
                     <p class="m-0 px-3"><?= __("Profile"); ?></p>
                  </li>
                  <li role="button" data-item="all-members" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                     <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                     <p class="m-0 px-3"><?= __("All members"); ?></p>
                  </li>
                  <li role="button" data-item="approved-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                     <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                     <p class="m-0 px-3"><?= __("Approved Pack"); ?></p>
                  </li>
                  <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                     <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                     <p class="m-0 px-3"><?= __("Pending Pack"); ?></p>
                  </li>
                  <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                     <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                     <p class="m-0 px-3"><?= __("Add Pack"); ?></p>
                  </li>
               </ul>
            </div>
            <div class="col-md-9 sidebar-content">
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <div class="content my-4 admin-profile-content d-block">
                  <h1 class="h1 text-center"><?= __("Admin Profile!"); ?></h1>
                  <p class="text-center"><?= __("Edit admin's informations"); ?></p>
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
                        echo $input_field->inputPhone("phone", $pn, true, false, $phone_code, $fabi_result->phone);
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
                     <button type="submit" name="update_profile_submit" class="btn btn-primary"><?= __("Update"); ?></button>
                  </form>
               </div>
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
                              <colgroup span="11"></colgroup>
                              <colgroup span="3"></colgroup>
                              <tr class="bg-warning text-white border-primary" scope="colgroup">
                                 <th colspan="11">Properties</th>
                                 <th colspan="3">Action</th>
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
                                 <th scope="col"><?= __("Role"); ?></th>

                                 <th scope="col"><?= __("Government ID"); ?></th>
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
                                             <td>" . $amr_key["role"] . "</td>

                                             <td><a href='/uploads/" . $amr_key["govt_id"] . "' class='btn btn-primary'>$vw</a></td>
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
                  <?php }                  ?>
               </div>
               <div class="content approved-pack-content d-none">
                  <!-- All stockvells (approved, pending, rejected)
                  See whoever requested to become leader -->
                  <h1 class="h1 text-center"><?= __("All approved packs"); ?>!</h1>
                  <p class="text-center"><?= __("You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper pieces of information."); ?>!</p>
                  <?php
                  // psr = pending stockvell result 
                  // $psr_result - getting from dashboard.inc.php
                  $nf = __("No pack found");
                  if (count($aasr_result) <= 0) {
                     echo "<div class='alert alert-warning'>$nf</div>";
                  } else { ?>
                     <div class="table-responsive">
                        <table class="table table-bordered border-warning">
                           <thead class="bg-warning text-white border-primary">
                              <colgroup span="10"></colgroup>
                              <colgroup span="2"></colgroup>
                              <tr class="bg-warning text-white border-primary">
                                 <th colspan="10">Properties</th>
                                 <th colspan="2">Action</th>
                              </tr>
                              <tr class="bg-warning text-white border-primary">
                                 <th scope="col"><?= __("ID"); ?></th>
                                 <th scope="col"><?= __("Name"); ?></th>
                                 <th scope="col"><?= __("Goal"); ?></th>
                                 <th scope="col"><?= __("Category"); ?></th>
                                 <th scope="col"><?= __("Status"); ?></th>
                                 <th scope="col"><?= __("Payment"); ?></th>
                                 <th scope="col"><?= __("Payment Period"); ?></th>
                                 <th scope="col"><?= __("Leader"); ?></th>
                                 <th scope="col"><?= __("Total Members"); ?></th>
                                 <th scope="col"><?= __("Withdraw Period"); ?></th>
                                 <th scope="col"><?= $vw ?></th>
                                 <th scope="col"><?= $et ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // psr = pending stockvell result 
                              // $psr_result - getting from dashboard.inc.php
                              foreach ($aasr_result as $aasr_key) {
                                 # code...
                                 echo "
                                       <tr class='text-capitalize'>
                                          <th>" . $aasr_key["id"] . "</th>
                                          <td>" . $aasr_key["name"] . "</td>
                                          <td>" . $aasr_key["goal"] . "</td>
                                          <td>" . $aasr_key["category"] . "</td>
                                          <td>" . $aasr_key["status"] . "</td>
                                          <td>" . $aasr_key["payment"] . "</td>
                                          <td>" . $aasr_key["payment_frequency"] . "</td>
                                          <td>" . $aasr_key["leader_id"] . "</td>
                                          <td>" . $aasr_key["totel_members"] . " </td>
                                          <td>" . $aasr_key["withdraw_frequency"] . "</td>
                                          <td><a href='/pack_single/?stockvell_id=" . $aasr_key["id"] . "' class='btn btn-primary'>$vw</a></td>
                                          <td><a href='/edit_stockvell/?stockvell_id=" . $aasr_key["id"] . "' class='btn btn-primary'>$et</a></td>
                                       </tr>
                                    ";
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  <?php }                  ?>
               </div>
               <div class="content pending-pack-content d-none">
                  <h1 class="h1 text-center"><?= __("All pending packs!"); ?></h1>
                  <p class="text-center"><?= __("You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!"); ?></p>
                  <?php
                  // psr = pending stockvell result 
                  // $apsr_result - getting from dashboard.inc.php

                  if (count($apsr_result) <= 0) {
                     echo "<div class='alert alert-warning'>$npf</div>";
                  } else { ?>
                     <div class="table-responsive">
                        <table class="table table-bordered border-warning">
                           <thead>
                              <colgroup span="9"></colgroup>
                              <colgroup span="2"></colgroup>
                              <tr class="bg-warning text-white border-primary">
                                 <th colspan="9">Properties</th>
                                 <th colspan="2">Action</th>
                              </tr>
                              <tr class="bg-warning text-white border-primary">
                                 <th scope="col">#<?= __("ID"); ?></th>
                                 <th scope="col"><?= __("Name"); ?></th>
                                 <th scope="col"><?= __("Goal"); ?></th>
                                 <th scope="col"><?= __("Category"); ?></th>
                                 <th scope="col"><?= __("Status"); ?></th>
                                 <th scope="col"><?= __("Payment"); ?></th>
                                 <th scope="col"><?= __("Payment Period"); ?></th>
                                 <th scope="col"><?= __("Leader"); ?></th>
                                 <th scope="col"><?= __("Withdraw Period"); ?></th>
                                 <th scope="col"><?= __("Previlage"); ?></th>
                                 <th scope="col"><?= __("Edit"); ?></th>
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
                                       <td>" . $apsr_key["goal"] . "</td>
                                       <td>" . $apsr_key["category"] . "</td>
                                       <td>" . $apsr_key["status"] . "</td>
                                       <td>" . $apsr_key["payment"] . "</td>
                                       <td>" . $apsr_key["payment_frequency"] . "</td>
                                       <td>" . $apsr_key["firstname"] . " " . $apsr_key["surname"] . "</td>
                                       <td>" . $apsr_key["withdraw_frequency"] . "</td>
                                       <td>
                                       <form class='p-0 m-0' action='/includes/admin.inc.php?stockvell_id=" . $apsr_key["id"] . "&leader_id=" . $apsr_key["leader_id"] . "' method='post'>
                                       <button type='submit' name='approve_stockvell' class='btn btn-primary'>$av</button>  
                                       </form>
                                       </td>
                                       <td><a href='/edit_stockvell/?stockvell_id=" . $apsr_key["id"] . "' class='btn btn-warning text-white'>Edit</a></td>
                                    </tr>
                                 ";
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  <?php } ?>
               </div>
               <div class="content add-pack-content d-none my-4">
                  <h1 class="h1 text-center"><?= __("Add stockvell pack"); ?>!</h1>
                  <p class="text-center"><?= __("This pack will be added as a pending pack that will don't be available to everyone until the admin approves it."); ?>!</p>

                  <!-- Form start  -->
                  <form action="/includes/admin.inc.php" method="POST">
                     <div class="row mb-3">
                        <?php // echo inputElement('name', 'Name*', false, 'text'); 
                        $nm = __("Name*");
                        $gl = __("Goal*");
                        $pyt = __("Payment*");
                        $pytf = __("Payment Frequency(days)*");
                        $wdf = __("Withdraw Frequency(days)*");
                        $ct = __("Category*");
                        $dsc = __("Description*");
                        $agmt = __("You Must Write Agreement About This Stockvell Pack*");
                        echo $input_field->inputText("name", $nm, false, "text", true);
                        echo $input_field->inputText("goal", $gl, false, "text", true);
                        ?>

                     </div>
                     <div class="row mb-3">
                        <?php
                        echo $input_field->inputTextarea("description", $dsc, true, true);
                        ?>
                     </div>
                     <div class="row mb-3">
                        <?php
                        echo $input_field->inputText('payment', $pyt, false, 'number', true);
                        echo $input_field->inputSelect("payment_frequency", $pytf, false, null, $freq_days);
                        ?>
                     </div>
                     <div class="row mb-3">
                        <?php
                        echo $input_field->inputSelect("category", $ct, false, null, ["social", "professional", "investmant"]);
                        echo $input_field->inputSelect("withdraw_frequency", $wdf, false, null, $freq_days);
                        ?>
                     </div>
                     <div class="row mb-3">
                        <?php
                        echo $input_field->inputTextarea("agreement", $agmt, true, true)
                        ?>
                     </div>


                     <button type="submit" name="create_stockvell_pack" class="btn btn-primary"><?= __("Create Stockvell") ?></button>
                  </form>
                  <!-- Form end  -->
               </div>
            </div>
         </div>
      </section>
      <!-- Authenticated content end  -->
   <?php
   } else {
   ?>
      <!-- unauthenticated content start  -->
      <div class="section-1">
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
               <button type="submit" name="login_admin_submit" class="btn btn-primary"><?= __("Login"); ?></button>
            </form>
         </div>
      </div>
      <!-- unauthenticated content end  -->
   <?php
   }
   ?>
</main>

<?php require_once("./layouts/footer.php"); ?>