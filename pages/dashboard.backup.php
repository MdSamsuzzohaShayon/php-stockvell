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

$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];


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

$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
    $has_error = true;
    $err_handler->setCommonErrors($_GET["error"]);
}

$input_field = new InputField();

?>



<main class="dashboard">
   <section class="section-1">
      <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
         <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
            <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
               <li role="button" data-item="profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                  <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                  <p class="m-0 px-3"><?= __("Profile"); ?></p>
               </li>
               <li role="button" data-item="my-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                  <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                  <p class="m-0 px-3"><?= __("My Pack"); ?></p>
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
            <div class="content my-4 profile-content d-block">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Update your informations!") ?></h1>
                  <p>You can change any field</p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <!-- Signup Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3">
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
                     $src = __('How did you hear about the Stockvell platform? (Optional)');

                     echo $input_field->inputText("firstname", $fn, false, "text", false, $cmr_result->firstname);
                     echo $input_field->inputText("surname", $sn, false, "text", false, $cmr_result->surname);
                     ?>
                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputText("email", $el, true, "email", false, $cmr_result->email);
                     ?>
                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputText("password", $pw, false, "password", false);
                     echo $input_field->inputText("password2", $pw, false, "password", false);
                     ?>

                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputSelect("country", $cy, false, $cmr_result->country, $countries_code);
                     // echo $input_field->inputPhone("phone", $pn, false, false, $cmr_result->phone);
                     echo $input_field->inputPhone("phone", $pn, false, true, $phone_code, $cmr_result->phone);
                     ?>
                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputText("city", $cty, false, "text", false, $cmr_result->city);
                     echo $input_field->inputSelect("gender", $gr, false, $cmr_result->gender, ["male", "female", "others"]);
                     ?>
                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputSelect("profession", $pro, false, $cmr_result->profession, $professions);
                     echo $input_field->inputFile("govt_id", $gid, false);
                     ?>
                  </div>
                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputTextarea("interest", $ist, true, false, $cmr_result->interest);
                     ?>
                  </div>


                  <div class="row mb-3">
                     <?php
                     echo $input_field->inputTextarea("source", $src, true, false, $cmr_result->source);
                     ?>
                  </div>

                  <button type="submit" name="member_update_submit" class="btn btn-primary"><?= __("Update"); ?></button>
               </form>
               <!-- Signup Form end  -->
            </div>
            <div class="content my-pack-content d-none">
               <h1 class="h1 text-center"><?= __("All of my pack!") ?></h1>
               <p class="text-center"><?= __("All the pack that you are a member of.") ?></p>
               <?php

               if (count($asr_result) <= 0) {
                  echo "<div class='alert alert-warning'>No pack found</div>";
               } else { ?>
                  <div class="table-responsive">
                     <table class="table table-bordered border-warning">
                        <thead class="bg-warning text-white border-primary">
                           <tr>
                              <th scope="col">#<?= __("ID") ?></th>
                              <th scope="col"><?= __("Name") ?></th>
                              <th scope="col"><?= __("Goal") ?></th>
                              <th scope="col"><?= __("Category") ?></th>
                              <th scope="col"><?= __("Status") ?></th>
                              <th scope="col"><?= __("Payment") ?></th>
                              <th scope="col"><?= __("Payment Period") ?></th>
                              <th scope="col"><?= __("Withdraw Period") ?></th>
                              <th scope="col"><?= __("Request to be the leader") ?></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           // psr = pending stockvell result 
                           // $asr_result - getting from dashboard.inc.php
                           if (count($asr_result) <= 0) {
                              echo "<div class='alert alert-warning'>No pack found</div>";
                           } else {
                              foreach ($asr_result as $asr_key) {
                                 # code...
                                 echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $asr_key["id"] . "</th>
                                    <td>" . $asr_key["name"] . "</td>
                                    <td>" . $asr_key["goal"] . "</td>
                                    <td>" . $asr_key["category"] . "</td>
                                    <td>" . $asr_key["status"] . "</td>
                                    <td>" . $asr_key["payment"] . "</td>
                                    <td>" . $asr_key["payment_frequency"] . "</td>
                                    <td>" . $asr_key["withdraw_frequency"] . "</td>
                                    <td><button class='btn btn-primary'>Request Leader</button></td>
                                 </tr>
                              ";
                              }
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               <?php }               ?>
            </div>
            <div class="content pending-pack-content d-none">
               <h1 class="h1"><?= __("All pending packs!") ?></h1>
               <p><?= __("You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!") ?></p>
               <?php
               // psr = pending stockvell result 
               // $psr_result - getting from dashboard.inc.php
               if (count($psr_result) <= 0) {
                  echo "<div class='alert alert-warning'>No pack found</div>";
               } else { ?>
                  <div class="table-responsive">
                     <table class="table table-bordered border-warning">
                        <thead class="bg-warning text-white border-primary">
                           <tr>
                              <th scope="col">#ID</th>
                              <th scope="col">Name</th>
                              <th scope="col">Goal</th>
                              <th scope="col">Category</th>
                              <th scope="col">Status</th>
                              <th scope="col">Payment</th>
                              <th scope="col">Payment Period</th>
                              <th scope="col">Leader</th>
                              <th scope="col">Total Members (u)</th>
                              <th scope="col">Withdraw Period</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           // psr = pending stockvell result 
                           // $psr_result - getting from dashboard.inc.php
                           if (count($psr_result) <= 0) {
                              echo "<div class='alert alert-warning'>No pack found</div>";
                           } else {
                              foreach ($psr_result as $psr_key) {
                                 # code...
                                 echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $psr_key["id"] . "</th>
                                    <td>" . $psr_key["name"] . "</td>
                                    <td>" . $psr_key["goal"] . "</td>
                                    <td>" . $psr_key["category"] . "</td>
                                    <td>" . $psr_key["status"] . "</td>
                                    <td>" . $psr_key["payment"] . "</td>
                                    <td>" . $psr_key["payment_frequency"] . "</td>
                                    <td>" . $psr_key["leader_id"] . "</td>
                                    <td> 10 </td>
                                    <td>" . $psr_key["withdraw_frequency"] . "</td>
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
            <div class="content add-pack-content d-none my-4">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Create your own Stockvell pack!") ?></h1>
                  <p><?= __("You can create your own stockvell, in order to do that you need to fill in all the input fields and once you create your will request of creating a new pack will be under our review.") ?></p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <!-- Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST">
                  <div class="row mb-3">
                     <?php // echo inputElement('name', 'Name*', false, 'text'); 
                     $nm = __("Name*");
                     $gl = __("Goal*");
                     $pyt = __("Payment*");
                     $pytf = __("Payment Frequency(days)*");
                     $wdf = __("Withdraw Frequency(days)*");
                     $ct = __("Category*");
                     $agmt = __("You Must Write Agreement About This Stockvell Pack*");
                     echo $input_field->inputText("name", $nm, false, "text", true);
                     echo $input_field->inputText("goal", $gl, false, "text", true);
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


                  <button type="submit" name="create-stockvell" class="btn btn-primary">Create Stockvell</button>
               </form>
               <!-- Form end  -->
            </div>
         </div>
      </div>
   </section>
</main>

<?php require_once("./layouts/footer.php"); ?>