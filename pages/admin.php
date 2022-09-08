<?php
session_start(); // In every single page we should start our session at the top of our code
if (isset($_SESSION['member_id'])) {
   header("Location: /dashboard.php");
   exit();
}
$admin_id = $_SESSION['admin_id'];
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$logged_admin = false;
if (isset($admin_id)) $logged_admin = true;

// Check for session 
require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
// require_once($ROOT . "/utils/input-fields.php");
// require_once($ROOT . "/classes/input.classes.php");

use Utils\ErrorHandler;
use Utils\InputField;

$err_arr = [];
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
   $err_arr = $err_handler->setCommonErrors($_GET["error"]);
}


$input_field = new InputField();
?>



<main class="admin">
   <!-- This section is for authenticated admin  -->
   <?php
   if ($logged_admin) {
      require_once($ROOT . "/includes/admin.inc.php");
   ?>
      <!-- Authenticated content start  -->
      <section class="section-2">
         <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
            <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
               <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
                  <li role="button" data-item="all-members" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                     <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                     <p class="m-0 px-3">All members</p>
                  </li>
                  <li role="button" data-item="approved-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                     <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                     <p class="m-0 px-3">Approved Pack</p>
                  </li>
                  <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                     <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                     <p class="m-0 px-3">Pending Pack</p>
                  </li>
                  <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                     <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                     <p class="m-0 px-3">Add Pack</p>
                  </li>
               </ul>
            </div>
            <div class="col-md-9 sidebar-content">
               <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
               <div class="content my-4 all-members-content d-block">
                  <h1 class="h1 text-center">All members!</h1>
                  <p class="text-center">All members informations which is verified or not</p>
                  <?php
                  // psr = pending stockvell result 
                  // $psr_result - getting from dashboard.inc.php
                  if (count($amr_result) <= 0) {
                     echo "<div class='alert alert-warning'>No pack found</div>";
                  } else { ?>
                     <div class="table-responsive">
                        <table class="table table-bordered border-warning">
                           <thead class="bg-warning text-white border-primary">
                              <tr>
                                 <th scope="col">#ID</th>
                                 <th scope="col">Firstname</th>
                                 <th scope="col">Sourname</th>
                                 <th scope="col">email</th>
                                 <th scope="col">Country</th>
                                 <th scope="col">Phone</th>
                                 <th scope="col">Gender</th>
                                 <th scope="col">Profession</th>
                                 <th scope="col">Interest</th>
                                 <th scope="col">Source</th>
                                 <th scope="col">Role</th>
                                 <th scope="col">Government ID</th>
                                 <th scope="col">Verification</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // amr = all member result
                              // id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role

                              foreach ($amr_result as $amr_key) {
                                 # If not verified add a form to verify
                                 $verified_content = "<form class='p-0 m-0' action='/includes/admin.inc.php?member_id=" . $amr_key["id"] . "' method='post'>
                                                         <button type='submit' name='verify_member' class='btn btn-warning'>Approve</button>  
                                                      </form>";
                                 // If already verified show the text verified
                                 if (intval($amr_key['is_verified']) === 1) {
                                    $verified_content = "Verified";
                                 }
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
                                             <td><a href='/uploads/" . $amr_key["govt_id"] . "' class='btn btn-primary'>View</a></td>
                                             <td>$verified_content</td>
                                          </tr>
                                       ";
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
                  <h1 class="h1 text-center">All approved packs!</h1>
                  <p class="text-center">You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!</p>
                  <?php
                  // psr = pending stockvell result 
                  // $psr_result - getting from dashboard.inc.php
                  if (count($aasr_result) <= 0) {
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
                                 <th scope="col">Total Members</th>
                                 <th scope="col">Withdraw Period</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // psr = pending stockvell result 
                              // $psr_result - getting from dashboard.inc.php
                              foreach ($aasr_result as $aasr_key) {
                                 # code...
                                 echo "
                                       <tr class='text-lowercase'>
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
                                          <td><a href='/pack_single/?stockvell_id=" . $aasr_key["id"] . "' class='btn btn-primary'>View</a></td>
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
                  <h1 class="h1 text-center">All pending packs!</h1>
                  <p class="text-center">You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!</p>
                  <?php
                  // psr = pending stockvell result 
                  // $apsr_result - getting from dashboard.inc.php
                  if (count($apsr_result) <= 0) {
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
                                 <th scope="col">Withdraw Period</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php
                              // psr = pending stockvell result 
                              // $apsr_result - getting from dashboard.inc.php
                              foreach ($apsr_result as $apsr_key) {
                                 # code...
                                 echo "
                                    <tr class='text-lowercase'>
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
                                             <button type='submit' name='approve_stockvell' class='btn btn-primary'>Approve</button>  
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
               <div class="content add-pack-content d-none my-4">
                  add or Update stockvell
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
               <h1 class="h1 text-center">Welcome to stockvell</h1>
               <p class="text-center">Please enter the followings</p>
            </div>
            <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
            <form action="/includes/login.inc.php" method="POST">
               <div class="row mb-3">
                  <?php
                  $password = __("Password");
                  echo $input_field->inputText("email", "Email", false, "email", true);
                  echo $input_field->inputText("password", $password, false, "password", true);
                  ?>
               </div>
               <button type="submit" name="login_admin_submit" class="btn btn-primary">login</button>
            </form>
         </div>
      </div>
      <!-- unauthenticated content end  -->
   <?php
   }
   ?>
</main>

<?php require_once("./layouts/footer.php"); ?>